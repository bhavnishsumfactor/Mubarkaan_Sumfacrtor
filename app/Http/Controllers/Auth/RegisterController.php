<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\YokartController;
use App\Models\User;
use App\Models\Country;
use App\Models\Configuration;
use App\Models\Package;
use App\Models\UserAuthToken;
use App\Models\SmsTemplate;
use App\Models\ShareEarnRecord;
use App\Models\AppReferralCodeLog;
use App\Jobs\SendNotification;
use App\Models\Admin\Admin;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Auth;
use Carbon\Carbon;
use Crypt;
use Session;
use Str;
use App\Events\SystemNotification;
use App\Models\NotificationTemplate;
use App\Models\Notification;
use App\Models\NewsletterSubscription;
use App\Helpers\EmailHelper;

class RegisterController extends YokartController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $userIsGuest = false)
    {
        $rules = [
            'user_fname' => ['required', 'string', 'max:150'],
            'user_lname' => ['required', 'string', 'max:150'],
            'user_password' => ['required', 'string', 'min:8', 'confirmed']
        ];
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
        $userData = [
            'user_fname' => $data['user_fname'],
            'user_lname' => $data['user_lname'],
            'user_email' => ($data['user_email'] ?? null),
            'user_phone' => ($data['user_phone'] ?? null),
            'user_password' => Hash::make($data['user_password']),
            'user_publish' => config("constants.YES")
        ];
        if (array_key_exists("user_phone", $data)) {
            $userData['user_phone_country_id'] = $data['user_phone_country_id'];
        }
        return User::create($userData);
    }

    protected function update(array $data)
    {
        User::where('user_id', $data['user_id'])->update([
            'user_fname' => $data['user_fname'],
            'user_lname' => $data['user_lname'],
            'user_password' => Hash::make($data['user_password'])
        ]);
        $user = User::where('user_id', $data['user_id'])->first();
        return $user;
    }

    public function register(Request $request)
    {
       
        $configurationData = Configuration::getKeyValues(['GOOGLE_RECAPCHA_SECRET_KEY', 'GOOGLE_RECAPCHA_STATUS', 'VERIFICATION_EMAIL_STATUS', 'ADMIN_TIMEZONE']);
        if (!empty($secretKey = $configurationData['GOOGLE_RECAPCHA_SECRET_KEY']) && $configurationData['GOOGLE_RECAPCHA_STATUS'] == 1) {
            $result = $this->googleRecaptchaVerification($request->get('recaptcha'), $request->ip(), $secretKey);
            if ($result->success != true) {
                toastr()->error(__('NOTI_SOMETHING_WENT_WRONG'));
                return back()->withInput($request->only('username', 'remember'));
            }
            if ($result->score < 0.3) {
                toastr()->error(__('NOTI_SOMETHING_WENT_WRONG'));
                return back();
            }
        }

        $requestData = $request->all();
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

        $this->validator($requestData, $userIsGuest)->validate();

        if (empty($guestUser)) {
            event(new Registered($user = $this->create($requestData))); /*if new user */
        } else {
            event(new Registered($user = $this->update($requestData))); /*if existing guest user signs up */
        }

        if ($signupType == "email") {
            if (!empty($guestUser) || $configurationData['VERIFICATION_EMAIL_STATUS'] == 1) {
                $this->sendCodeOnEmail($user);
            } else {
                if (empty($guestUser)) {
                    $user->user_email_verified = config("constants.YES");
                }
                if ($request->input('referralcode') != '') {
                    ShareEarnRecord::addRewardPoints($request->input('referralcode'), $user->user_id);
                }
            }
        }

        if ($signupType == "phone") {
            $smsPackage = Package::getActivePackage('sms');
            if (!empty($smsPackage)) {
                $this->sendCodeOnPhone($user, $country->country_phonecode);
            } else {
                $user->user_phone_verified = config("constants.YES");
                if ($request->input('referralcode') != '') {
                    ShareEarnRecord::addRewardPoints($request->input('referralcode'), $user->user_id);
                }
            }
        }
        
        if (!empty($request->ip())) {
            $locationData = \Location::get($request->ip());
            if (!empty($locationData->countryCode)) {
                $countryId = Country::getCountries(['country_id'], ['country_code' => $locationData->countryCode])->pluck("country_id")->first();
                $user->user_country_id = ($countryId ?? 0);
            }
        } else {
            $user->user_country_id = 0;
        }

        $user->user_timezone = $configurationData['ADMIN_TIMEZONE'];
        $user->user_referral_code = $request->input('referralcode');
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
        $userId = Crypt::encrypt($user->user_id);
        $cookie = $this->makeCookie('Register', $userId, [15, null, null, false, false]);
        return $this->registered($request, $user) ? : redirect('register/success/' . $userId . '/' . $signupType)->with('registered', true)->withCookie($cookie);
    }

    public function showRegistrationForm(Request $request)
    {
        if (Auth::check()) {
            return redirect('/home');
        }
        $referralCode =  $request->input('referralcode');
      
        if ($referralCode) {
            
            $ipAddress = \Request::ip();
            $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
            $ipod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
            if (AppReferralCodeLog::where(['arcl_ip_address' => $ipAddress, 'arcl_referral_code' => $referralCode])->count() == 0) {
               
                AppReferralCodeLog::create([
                    'arcl_ip_address' => $ipAddress,
                    'arcl_referral_code' => $referralCode,
                    'arcl_device' => $_SERVER['HTTP_USER_AGENT']
                ]);
            }
            if ($iphone || $ipod) {
                echo "<script>window.location='" . env('ANDROID_APP_URL') . "'</script>";
            } elseif ($android) {
                echo "<script>window.location.href='" . env('IOS_APP_URL') . "'</script>";
            }
        }
        $recaptchaData = Configuration::getKeyValues(['GOOGLE_RECAPCHA_SITE_KEY','GOOGLE_RECAPCHA_STATUS']);
        $smsPackage = Package::getActivePackage('sms');
        $defaultCountry = getDefaultCountryCode();
        return view('themes.' . config('theme') . '.register', compact('recaptchaData', 'referralCode', 'smsPackage', 'defaultCountry'));
    }
   
    /* account verification start */
    public function accountVerificationPage(Request $request, $slug, $loginType)
    {
        $registerCookie = $this->retrieveCookie('Register');
        /*if (empty($registerCookie) && $slug != $registerCookie) {
            return redirect('/login');
        }*/
        $decryptedUserId = Crypt::decrypt($slug);
        $user = User::select('user_id', 'user_fname', 'user_lname', 'user_phone_verified', 'user_email_verified', 'user_email', 'user_phone_country_id', 'user_phone')
            ->where('user_id', $decryptedUserId)
            ->first();
        if (empty($user)) {
            abort(404);
        }

        $step = '';
        $sendCode = Session::get('sendCode');
        $smsPackage = Package::getActivePackage('sms');
        if ($loginType == "email") {
            $step = 'email-verify';
            if (!empty($sendCode) && $sendCode == 1) {
                $this->sendCodeOnEmail($user);
            }
        } elseif ($loginType == "phone" && !empty($smsPackage)) {
            $step = 'phone-verify';
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $user->user_phone_country_id])->first();
            $user->user_phone = '+' . $country->country_phonecode . $user->user_phone;
            if (!empty($sendCode) && $sendCode == 1) {
                $this->sendCodeOnPhone($user, $country->country_phonecode);
            }
        }
        
        return view('themes.' . config('theme') . '.accountVerification', compact('user', 'step', 'slug', 'loginType'));
    }
    /* account verification end */

    /* signup verification start */
    public function registerSuccessPage(Request $request, $slug, $signupType)
    {
        $registerCookie = $this->retrieveCookie('Register');
        // dd($registerCookie);
        if (empty($registerCookie) && $slug != $registerCookie) {
            return redirect('/login');
        }
        $decryptedUserId = Crypt::decrypt($slug);
        $user = User::select('user_id', 'user_phone_verified', 'user_email_verified', 'user_email', 'user_phone_country_id', 'user_phone', 'user_is_guest')
            ->where('user_id', $decryptedUserId)
            ->first();
        if (empty($user)) {
            abort(404);
        }
        
        $step = 'signup';
        if ($signupType == "email" && (getConfigValueByName('VERIFICATION_EMAIL_STATUS') == 1 || $user->user_is_guest == config("constants.YES"))) {
            $step = 'signup-email-verify';
        }
        if ($signupType == "phone") {
            $smsPackage = Package::getActivePackage('sms');
            if (!empty($smsPackage)) {
                $step = 'signup-phone-verify';
                $country = Country::getCountries(['country_phonecode'], ['country_id' => $user->user_phone_country_id])->first();
                $user->user_phone = '+' . $country->country_phonecode . $user->user_phone;
            }
        }
        return view('themes.' . config('theme') . '.registerSuccess', compact('user', 'step', 'slug', 'signupType'));
    }

    public function resendVerificationCode(Request $request)
    {
        UserAuthToken::where('usrauth_expiry', '<', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())->delete();
        $decryptedUserId = Crypt::decrypt($request->input('slug'));
        $user = User::where('user_id', $decryptedUserId)->first();
        $country = Country::getCountries(['country_phonecode'], ['country_id' => $user->user_phone_country_id])->first();
        if (!empty($user->user_email) && $user->user_email_verified == 0) {
            $this->sendCodeOnEmail($user);
        } elseif (!empty($user->user_phone)) {
            $this->sendCodeOnPhone($user, $country->country_phonecode);
        }
        
        return jsonresponse(true, '');
    }

    public function verifyCode(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required',
        ], [
            'otp.required' => __('MSG_OTP_IS_REQUIRED'),
        ]);
        $decryptedUserId = Crypt::decrypt($request->input('slug'));
        $user = User::where('user_id', $decryptedUserId)->first();
        
        $userAuthToken = UserAuthToken::where('usrauth_user_id', $user->user_id)
            ->where('usrauth_token', $request['otp'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
        if (!empty($userAuthToken)) {
            $updateData = [];
            
            if ($request->input('type') == "email") {
                $updateData['user_email_verified'] = 1;
                $updateData['user_phone'] = '';
            } elseif ($request->input('type') == "phone") {
                $updateData['user_phone_verified'] = 1;
                $updateData['user_email'] = '';
            }
            if ($user->user_is_guest == 1) {
                $updateData['user_is_guest'] = 0;
            }
            if (!empty($user->user_referral_code)) {
                ShareEarnRecord::addRewardPoints($user->user_referral_code, $user->user_id);
            }
            User::where('user_id', $decryptedUserId)->update($updateData);

            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();

            return jsonresponse(true, __('NOTI_OTP_VERIFIED'));
        }
        return jsonresponse(false, __('NOTI_ENTER_CORRECT_OTP'));
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
    /* signup verification end */

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
