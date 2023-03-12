<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Str;
use App\Models\User;
use App\Models\Country;
use App\Models\Package;
use App\Models\UserAuthToken;
use App\Models\SmsTemplate;
use App\Models\AppReferralCodeLog;
use App\Models\Notification;
use App\Models\ShareEarnRecord;
use App\Models\Admin\Admin;
use App\Models\NotificationTemplate;
use App\Models\NewsletterSubscription;
use App\Helpers\EmailHelper;
use App\Events\SystemNotification;
use App\Jobs\SendNotification;
use Auth;

class RegisterController extends YokartController
{
    public function register(Request $request)
    {
        
        $requestData = $request->all();
        $ipAddress = \Request::ip();
        $signupType = "email";
        if (array_key_exists("user_phone", $requestData)) {
            if (empty($requestData['user_phone_country_code'])) {
                $requestData['user_phone_country_code'] = 'US';
            }
            $country = Country::getCountries(['country_id', 'country_phonecode'], ['country_code' => strtoupper($requestData['user_phone_country_code'])])->first();
            if (!empty($country->country_id)) {
                $requestData['user_phone_country_id'] = $country->country_id;
            }
            $signupType = "phone";
        }
        
        $guestUserq = User::select('user_id', 'user_publish', 'user_is_guest');
        if ($signupType == "email" && !empty($requestData["user_email"])) {
            $guestUserq->where('user_email', $requestData['user_email']);
        } elseif ($signupType == "phone" && !empty($requestData["user_phone"])) {
            $guestUserq->where('user_phone_country_id', $requestData['user_phone_country_id'])
                ->where('user_phone', $requestData['user_phone']);
        }
        $guestUserq->where('user_is_guest', config("constants.YES"));
        $guestUser = $guestUserq->first();
        
        $userIsGuest = false;
        if (!empty($guestUser)) {
            $userIsGuest = true;
            $requestData['user_id'] = $guestUser->user_id;
        }

        $validator = $this->validator($requestData, $userIsGuest); //->validate()

        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }

        if (empty($guestUser)) {
            event(new Registered($user = $this->create($requestData))); /*if new user */
        } else {
            event(new Registered($user = $this->update($requestData))); /*if existing guest user signs up */
        }
        $response = [
            'verification_required' => '0'
        ];
        $registerType = USER::USER_EMAIL_REGISTER_TYPE;
        $configurationData = getConfigValues(['APP_VERIFICATION_EMAIL_STATUS', 'ADMIN_TIMEZONE']);
        if ($signupType == "email") {
            if (!empty($guestUser) || $configurationData['APP_VERIFICATION_EMAIL_STATUS'] == 1) {
                $this->sendCodeOnEmail($user);
                $response['verification_required'] = "1";
            } else {
                if (empty($guestUser)) {
                    $user->user_email_verified = config("constants.YES");
                }
            }
        }
      
        if ($signupType == "phone") {
            $registerType = USER::USER_PHONE_REGISTER_TYPE;
            $smsPackage = Package::getActivePackage('sms');
           
            if (!empty($smsPackage)) {
                $this->sendCodeOnPhone($user, $country->country_phonecode);
                $response['verification_required'] = "1";
            } else {
                $user->user_phone_verified = config("constants.YES");
            }
        }
        $referralCode = '';
        $referralRecord = AppReferralCodeLog::where(['arcl_ip_address' => $ipAddress, 'arcl_status' => 0]) ->first();

        if (!empty($referralRecord)) {
            $referralCode = $referralRecord->arcl_referral_code;
            ShareEarnRecord::addRewardPoints($referralCode, $user->user_id);
            AppReferralCodeLog::where(['arcl_ip_address' => $ipAddress, 'arcl_referral_code' => $referralCode])->update(['arcl_status' => 1]);
        }
        $user->user_country_id = !empty($country->country_id) ? $country->country_id : 0;
        $user->user_timezone = $configurationData['ADMIN_TIMEZONE'];
        $user->user_referral_code = $referralCode;
        $user->user_register_type = $registerType;
        $user->save();
        
        if ($request->get('subscribe') && !empty($user->user_email)) {
            NewsletterSubscription::subscribe($user->user_email);
        }
        /*send email code*/
        $notificationData = [];
        $sendSms = false;
        $data = EmailHelper::getEmailData('welcome');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $this->replacementTags($data['body']->etpl_subject, $user, false);
        $data['body'] = $this->replacementTags($data['body']->etpl_body, $user, false);
        $data['to'] = $user->user_email;
        $notificationData['email'] = $data;

        if (!empty($user->user_phone) && !empty($user->user_phone_country_id)) {
            $smsdata = SmsTemplate::getSMSTemplate('welcome');
            $country = Country::select('country_phonecode')->where('country_id', $user->user_phone_country_id)->first();
            $smsdata['body'] = str_replace('{name}', $user->fname.' '.$user->lname, $smsdata['body']->stpl_body);
            $smsdata['body'] = str_replace('{websiteName}', env('APP_NAME'), $smsdata['body']);
            $smsdata['body'] = str_replace('{websiteUrl}', url('/'), $smsdata['body']);

            $notificationData['sms'] = [
                'message' => $smsdata['body'],
                'recipients' => array('+' . $country->country_phonecode . $user->user_phone)
            ];
            $sendSms = true;
        }

       
        dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
        /** send email notification to the admin */
        $adminData = Admin::getSuperAdminId();
        $adminEmailData = EmailHelper::getEmailData('signup_up_notification_admin');
        $priority = $adminEmailData['body']->etpl_priority;
        $adminEmailData['subject'] = $this->adminEmailReplacementTags($adminEmailData['body']->etpl_subject, $user, $signupType, $adminData);
        $adminEmailData['body'] = $this->adminEmailReplacementTags($adminEmailData['body']->etpl_body, $user, $signupType, $adminData);
        $adminEmailData['to'] = $adminData->admin_email;
        dispatch(new SendNotification(array('email' => $adminEmailData)))->onQueue($priority);


        /** trigger event for system notification */
        $data = NotificationTemplate::getBySlug('new_user_registered');
        $message = str_replace('{email}', $user->user_email, $data->ntpl_body);
        $message = str_replace('{userName}', Str::title($user->user_fname . ' ' . $user->user_lname), $message);
        event(new SystemNotification([
            'type' => Notification::USER_REGISTER,
            'record_id' => $user->user_id,
            'from_id' => $user->user_id,
            'message' => $message
        ]));
        $response['user_id'] = $user->user_id;
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_SIGNUP_SUCCESSFULL"), $response);
    }

    protected function validator(array $data, $userIsGuest = false)
    {
        if (array_key_exists("user_phone", $data)) {
            $tempRules = [ 'required', 'numeric' ];
            if ($userIsGuest === false) {
                $tempRules[] = Rule::unique('users')->where('user_phone_country_id', $data['user_phone_country_id']);
            }
            $rules['user_phone'] = $tempRules;
        }
        if (array_key_exists("user_email", $data)) {
            $tempRules = ['required', 'string', 'email', 'max:255'];
            if ($userIsGuest === false) {
                $tempRules[] = 'unique:users';
            }
            $rules['user_email'] = $tempRules;
            $rules['user_password'] = ['required', 'string', 'min:8'];
        }
        
        $validator = Validator::make($data, $rules);
        
        return $validator->setAttributeNames([
            'user_fname' => 'first name',
            'user_lname' => 'last name',
            'user_email' => 'email',
            'user_password' => 'password',
            'user_phone' => 'phone'
        ]);
    }
    
    protected function create(array $data)
    {
        $firstName = "";
        $lastName = "";
        $password = "";
        if (isset($data['user_fname'])) {
            $firstName = $data['user_fname'];
            $lastName = $data['user_lname'];
            $password =  Hash::make($data['user_password']);
        }
        
        $userData = [
            'user_fname' => $firstName,
            'user_lname' => $lastName,
            'user_email' => ($data['user_email'] ?? null),
            'user_phone' => ($data['user_phone'] ?? null),
            'user_password' => $password,
            'user_publish' => config("constants.YES")
        ];
        if (array_key_exists("user_phone", $data)) {
            $userData['user_phone_country_id'] = $data['user_phone_country_id'];
        }
        return User::create($userData);
    }

    protected function update(array $data)
    {
        $nameArr = explode(" ", $data['user_name']);
        $firstName = ($nameArr[0] ?? '');
        array_shift($nameArr);
        $lastName = implode(" ", $nameArr);

        User::where('user_id', $data['user_id'])->update([
            'user_fname' => $firstName,
            'user_lname' => $lastName,
            'user_password' => Hash::make($data['user_password'])
        ]);
        $user = User::where('user_id', $data['user_id'])->first();
        return $user;
    }

    public function resendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        UserAuthToken::where('usrauth_expiry', '<', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())->delete();
        $user = User::where('user_id', $request->input('user_id'))->first();
        $country = Country::getCountries(['country_phonecode'], ['country_id' => $user->user_phone_country_id])->first();
        $message = "";
        if (!empty($user->user_email) && $user->user_email_verified == config("constants.NO")) {
            $this->sendCodeOnEmail($user);
            $message = appLang("NOTI_CODE_SENT_ON_EMAIL");
        } elseif (!empty($user->user_phone)) {
            $this->sendCodeOnPhone($user, $country->country_phonecode);
            $message = appLang("NOTI_CODE_SENT_ON_PHONE");
        }
        return apiresponse(config('constants.SUCCESS'), $message);
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'verification_code' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $userId = $request->input('user_id');
        $type = $request->input('type');
        $user = User::select('user_id')->where('user_id', $userId)->first();
        $response = [];
        $userAuthToken = UserAuthToken::where('usrauth_user_id', $user->user_id)
            ->where('usrauth_token', $request['verification_code'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
        if (!empty($userAuthToken)) {
            $updateData = [];
            if ($type == "email") {
                $updateData['user_email_verified'] = config("constants.YES");
            } elseif ($type == "phone") {
                $updateData['user_phone_verified'] = config("constants.YES");
            }
            if ($user->user_is_guest == config("constants.YES")) {
                $updateData['user_is_guest'] = config("constants.NO");
            }
            if (!empty($user->user_referral_code)) {
                ShareEarnRecord::addRewardPoints($user->user_referral_code, $user->user_id);
            }
            User::where('user_id', $userId)->update($updateData);

            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CODE_VERIFIED"), ['user_id' => (int)$userId]);
        }
        return apiresponse(config('constants.ERROR'), appLang("NOTI_ENTER_CORRECT_CODE"));
    }

    public function updateUserInfo(Request $request)
    {
        $userId = $request->input('user_id');
        $response  = User::updateProfileFromApp($request, $userId, true);
        if ($response['status'] == false) {
            return apiresponse(config('constants.ERROR'), $response['message']);
        }
        $user = User::getDetailsForApp($userId);
        Auth::loginUsingId($userId);
        $token = $user->createToken(env('APP_NAME') . ' Password Grant Client')->accessToken;
        $response = $user;
        $response['token'] = $token;
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CODE_VERIFIED"), $response);
    }
    private function sendCodeOnEmail($user)
    {
        $userAuthToken =  UserAuthToken::where('usrauth_user_id', $user->user_id)->where('usrauth_expiry', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())->first();
        $code = '';
        if (!empty($userAuthToken)) {
            $code = $userAuthToken->usrauth_token;
        } else {
            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();
            $code = mt_rand(1000, 9999);
            UserAuthToken::insert([
                'usrauth_user_id' => $user->user_id,
                'usrauth_token' => $code,
                'usrauth_expiry' => Carbon::now()
            ]);
        }
        /*send email code*/
        $data = EmailHelper::getEmailData('email_verify');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $this->replacementTags($data['body']->etpl_subject, $user, $code);
        $data['body'] = $this->replacementTags($data['body']->etpl_body, $user, $code);
        $data['to'] = $user->user_email;
        dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
    }

    private function replacementTags($type, $user, $code)
    {
        $type = str_replace('{name}', $user->user_fname . ' ' . $user->user_lname, $type);
        $type = str_replace('{businessEmail}', getConfigValueByName("BUSINESS_EMAIL"), $type);
        $type = str_replace('{shopUrl}', route('productListing'), $type);
        if ($code) {
            $type = str_replace('{verificationCode}', $code, $type);
        }
        $type = str_replace('{websiteName}', env('APP_NAME'), $type);
        return $type;
    }

    private function sendCodeOnPhone($user, $countryCode)
    {
        $userAuthToken =  UserAuthToken::where('usrauth_user_id', $user->user_id)->where('usrauth_expiry', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())->first();
        $code = '';
        if (!empty($userAuthToken)) {
            $code = $userAuthToken->usrauth_token;
        } else {
            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();
            $code = mt_rand(1000, 9999);
            UserAuthToken::insert([
                'usrauth_user_id' => $user->user_id,
                'usrauth_token' => $code,
                'usrauth_expiry' => Carbon::now()
            ]);
        }
            
        /*send sms code*/
        $data = SmsTemplate::getSMSTemplate('signup_verification');
        $priority = $data['body']->stpl_priority;
        $data['body'] = str_replace('{name}', $user->user_fname . ' ' . $user->user_lname, $data['body']->stpl_body);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $data['body'] = str_replace('{verificationCode}', $code, $data['body']);
        $notificationData['sms'] = [
            'message' => $data['body'],
            'recipients' => array('+' . $countryCode . $user->user_phone)
        ];
        dispatch(new SendNotification($notificationData, false, false, true))->onQueue($priority);
    }

    private function adminEmailReplacementTags($replacedData, $user, $signupType, $adminData)
    {
        $replacedData = str_replace('{userName}', $user->user_fname . ' ' . $user->user_lname, $replacedData);
        $replacedData = str_replace('{adminName}', $adminData->admin_name, $replacedData);
        $replacedData = str_replace('{signUpVia}', $signupType, $replacedData);
        $emailPhone = '';
        if ($signupType == 'email') {
            $emailPhone = $user->user_email;
        }
        if ($signupType == 'phone') {
            $emailPhone = $user->user_phone;
        }
        $replacedData = str_replace('{emailOrPhone}', $emailPhone, $replacedData);
        $replacedData = str_replace('{websiteName}', env('APP_NAME'), $replacedData);
        return $replacedData;
    }
}
