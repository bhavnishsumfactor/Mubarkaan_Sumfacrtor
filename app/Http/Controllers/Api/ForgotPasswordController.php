<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Country;
use App\Models\SmsTemplate;
use App\Models\Package;
use App\Models\UserAuthToken;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use Crypt;
use Carbon\Carbon;

class ForgotPasswordController extends YokartController
{
    public function forgotPassword(Request $request)
    {
        $type = "email";
        if (array_key_exists("phone", $request->all())) {
            $type = "phone";
        }
        $validationRules = [];
        if ($type == "phone") {
            $validationRules['phone'] = 'required';
            $validationRules['phone_code'] = 'required';
        } else {
            $validationRules['email'] = 'required|email';
        }
        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }

        $userQuery = User::select('user_id', 'user_fname', 'user_lname', 'user_phone', 'user_email')->where('user_publish', config('constants.YES'));
        if ($type == "phone") {
            $country = Country::getCountries(['country_id', 'country_phonecode'], ['country_code' => strtoupper($request->phone_code)])->first();
            $userQuery->where('user_phone', $request->phone)
            ->where('user_phone_country_id', $country->country_id)
            ->where('user_phone_verified', config('constants.YES'));
        } else {
            $userQuery->where('user_email', $request->email)
            ->where('user_email_verified', config('constants.YES'));
        }
        $user = $userQuery->first();
        if (empty($user)) {
            $message = "";
            $userQuery = User::select('user_id', 'user_phone', 'user_phone_verified', 'user_email_verified', 'user_publish');
            if ($type == "phone") {
                $userQuery->where('user_phone', $request->phone)
                ->where('user_phone_country_id', $country->country_id);
            } else {
                $userQuery->where('user_email', $request->email);
            }
            $user = $userQuery->first();
            if (empty($user)) {
                $message = appLang("NOTI_USER_DOES_NOT_EXIST");
            } else {
                if ($user->user_publish === config('constants.NO')) {
                    $message = appLang("NOTI_ACCOUNT_DISABLED");
                } elseif ($user->user_email_verified === config('constants.NO')) {
                    if ($type == "phone") {
                        $message = appLang("NOTI_PHONE_NOT_VERIFIED");
                    } else {
                        $message = appLang("NOTI_EMAIL_NOT_VERIFIED");
                    }
                }
            }
            return apiresponse(config('constants.ERROR'), $message);
        }
        $response = [
            'user_id' => $user->user_id
        ];
        if ($type == "phone") {
            $user->user_phone = '+' . $country->country_phonecode . $user->user_phone;
            $this->sendCodeOnPhone($user);
            $response['user_phone'] = $user->user_phone;
            $response['user_phone_code'] = '+' . $country->country_phonecode;
            $message = appLang("NOTI_CODE_SENT_ON_PHONE");
        } else {
            $this->sendCodeOnEmail($user);
            $response['user_email'] = $user->user_email;
            $message = appLang("NOTI_CODE_SENT_ON_EMAIL");
        }
        return apiresponse(config('constants.SUCCESS'), $message, $response);
    }

    public function resendVerificationCode(Request $request)
    {
        $userId = $request->input('user_id');
        $type = $request->input('type');
        UserAuthToken::where('usrauth_expiry', '<', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())->delete();
        $user = User::select('user_id', 'user_fname', 'user_lname', 'user_phone_country_id', 'user_phone', 'user_email')
            ->where('user_id', $userId)->first();
        $message = "";
        if ($type == 'email') {
            $this->sendCodeOnEmail($user);
            $message = appLang("NOTI_CODE_SENT_ON_EMAIL");
        } elseif ($type == 'phone') {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $user->user_phone_country_id])->first();
            if (empty($country->country_phonecode) || empty($user->user_phone)) {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_INVALID_REQUEST"));
            }
            $user->user_phone = '+' . $country->country_phonecode . $user->user_phone;
            $this->sendCodeOnPhone($user);
            $message = appLang("NOTI_CODE_SENT_ON_PHONE");
        }
        return apiresponse(config('constants.SUCCESS'), $message);
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'verification_code' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $user = User::where('user_id', $request->input('user_id'))->first();
        
        $userAuthToken = UserAuthToken::where('usrauth_user_id', $user->user_id)
            ->where('usrauth_token', $request->input('verification_code'))
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
            
        if (!empty($userAuthToken)) {
            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();
            $response = [
                'user_id' => $user->user_id
            ];
            return apiresponse(config('constants.SUCCESS'), appLang('NOTI_CODE_VERIFIED'), $response);
        }
        return apiresponse(config('constants.ERROR'), appLang('NOTI_ENTER_CORRECT_CODE'));
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'user_password' => 'required|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ], [
            'user_password.regex' => __('MSG_PASSWORD_VALIDATION')
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
       User::where('user_id', $request->input('user_id'))->update(['user_password' => Hash::make($request->input('user_password'))]);

        return apiresponse(config('constants.SUCCESS'), appLang('NOTI_PASSWORD_UPDATED'));
    }

    private function sendCodeOnEmail($user)
    {
        $userAuthToken =  UserAuthToken::where('usrauth_user_id', $user->user_id)->first();
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
        $data = EmailHelper::getEmailData('forgot_password');
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
        $type = str_replace('{verificationCode}', $code, $type);
        $type = str_replace('{websiteName}', env('APP_NAME'), $type);
        return $type;
    }
    private function sendCodeOnPhone($user)
    {
        $smsPackageCheck = Package::getPublishedPackages('sms');
        if ($smsPackageCheck->count() == 0) {
            return false;
        }
        $userAuthToken =  UserAuthToken::where('usrauth_user_id', $user->user_id)->first();
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
        $data = SmsTemplate::getSMSTemplate('forgot_password');
        $priority = $data['body']->stpl_priority;
        $data['body'] = str_replace('{name}', $user->user_fname . ' ' . $user->user_lname, $data['body']->stpl_body);
        $data['body'] = str_replace('{verificationCode}', $code, $data['body']);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $notificationData['sms'] = [
            'message' => $data['body'],
            'recipients' => array($user->user_phone)
        ];
        dispatch(new SendNotification($notificationData, false, false, true))->onQueue($priority);
    }
}
