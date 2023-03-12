<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\YokartController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Models\Admin\Admin;
use App\Models\User;
use App\Models\Admin\AdminPasswordResetRequest;
use App\Models\EmailTemplate;
use App\Models\Configuration;
use App\Models\Notification;
use App\Models\Country;
use App\Models\SmsTemplate;
use App\Models\Package;
use App\Models\UserPasswordReset;
use Illuminate\Support\Str;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use Illuminate\Contracts\Auth\PasswordBroker;
use Mail;
use Session;
use Crypt;
use Carbon\Carbon;

class ForgotPasswordController extends YokartController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function adminShowLinkRequestForm()
    {
        $smsPackageCheck = Package::getPublishedPackages('sms');
        $smsActivePackage = $smsPackageCheck->count();
        $defaultCountry = getDefaultCountryCode();
        return view('admin.auth.passwords.email', compact('smsActivePackage', 'defaultCountry'));
    }

    public function adminSendResetLinkEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ], [
            'email.required' => __('Please enter an email address'),
            'email.email' => __('Please enter a valid email address')
        ]);
        AdminPasswordResetRequest::where('aprr_expiry', '<', Carbon::now()->subMinutes(config('app.expiredToken'))->toDateTimeString())->delete();
        $admin = Admin::where('admin_email', $request->input('email'))->first();
        if ($admin) {
            $records =  AdminPasswordResetRequest::where('aprr_admin_id', $admin->admin_id)->first();
            $token = '';
            if (!empty($records)) {
                $token = $records->aprr_token;
            } else {
                $token = Str::random(64);
                AdminPasswordResetRequest::insert([
                    'aprr_admin_id' => $admin->admin_id,
                    'aprr_token' => $token,
                    'aprr_expiry' => Carbon::now()
                ]);
            }
            $this->sendResetEmail($admin, $token);
            return jsonresponse(true, __('NOTI_RESET_PASSWORD_INSTRUCTION_SENT'));
        }
        return jsonresponse(false, '', ['email' => __('NOTI_PLEASE_ENTER_VALID_EMAIL')]);
    }

    public function sendResetEmail($admin, $token)
    {
        /*send email code*/
        $data = EmailHelper::getEmailData('admin_forgot_password');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $this->replacementTags($data['body']->etpl_subject, $admin, $token);
        $data['body'] = $this->replacementTags($data['body']->etpl_body, $admin, $token);
        $data['to'] = $admin->admin_email;
        dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
    }

    private function replacementTags($type, $admin, $token)
    {
        $type = str_replace('{name}', $admin->admin_name, $type);
        $type = str_replace('{businessEmail}', getConfigValueByName("BUSINESS_EMAIL"), $type);
        $type = str_replace('{resetLink}', url('admin/password/reset', $token), $type);
        $type = str_replace('{websiteName}', env('APP_NAME'), $type);
        return $type;
    }

    public function adminSendResetOtpPhone(Request $request)
    {
        $this->validate($request, [
            'user_phone' => 'required|numeric',
        ], [
            'user_phone.required' => __('Please enter a valid phone number'),
            'user_phone.numeric' => __('Phone number should be numeric only')
        ]);
        AdminPasswordResetRequest::where('aprr_expiry', '<', Carbon::now()->subMinutes(config('app.expiredToken'))->toDateTimeString())->delete();
        $admin = Admin::where('admin_phone', $request->input('user_phone'))->first();
        $country = Country::select('country_id', 'country_phonecode')->where('country_code', Str::upper($request->input('user_phone_country_code')))->first();
        if (isset($admin) && !empty($admin) && isset($country) && !empty($country) && ($country->country_id == $admin->admin_phone_country_id)) {
            $this->sendotp($admin, $country->country_phonecode);
            \Session::flash('phone', $request->input('user_phone'));
            \Session::flash('phone_code', $country->country_phonecode);
            return jsonresponse(true, __('NOTI_OTP_SENT_ON_PHONE'));
        }

        return jsonresponse(false, '', ['user_phone' => __('NOTI_PHONE_NUMBER_INCORRECT')]);
    }

    public function adminVerifyOtp(Request $request)
    {
        if (empty($request->input('phone'))) {
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
        $this->validate($request, [
            'otp' => 'required',
        ], [
            'otp.required' => __('Please enter OTP'),
        ]);
        $admin = Admin::where('admin_phone', $request->input('phone'))->first();
        
        $aprr = AdminPasswordResetRequest::where('aprr_admin_id', $admin->admin_id)
            ->where('aprr_otp', $request['otp'])
            ->where('aprr_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredToken'))->toDateTimeString())
            ->first();
        if (!empty($aprr)) {
            $token = Str::random(64);
            AdminPasswordResetRequest::where('aprr_admin_id', $admin->admin_id)->update([
                'aprr_otp' => null,
                'aprr_token' => $token,
                'aprr_expiry' => Carbon::now()
            ]);
            $this->sendResetEmail($admin, $token);
            return jsonresponse(true, __('NOTI_OTP_VERIFY_SUCCESSFULLY'), ['url' => url('admin/password/reset', $token)]);
        }
        return jsonresponse(false, __('NOTI_PLEASE_ENTER_CORRECT_OTP'));
    }

    public function adminResendOtp(Request $request)
    {
        if (empty($request->input('phone'))) {
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
        }
        $admin = Admin::where('admin_phone', $request->input('phone'))->first();
        if (isset($admin->admin_id) && !empty($admin->admin_id)) {
            $this->sendotp($admin, $request->input('user_phone_code'));
            return jsonresponse(true, __('NOTI_OTP_RESEND_SUCCESSFULLY'));
        }
        return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
    }

    private function sendotp($admin, $countryPhoneCode)
    {
        AdminPasswordResetRequest::where('aprr_admin_id', $admin->admin_id)->delete();
        $verificationCode = mt_rand(1000, 9999);
        AdminPasswordResetRequest::insert([
            'aprr_admin_id' => $admin->admin_id,
            'aprr_otp' => $verificationCode,
            'aprr_expiry' => Carbon::now()
        ]);
        /*send sms code*/
        if(!empty($admin->admin_phone)) {
            $data = SmsTemplate::getSMSTemplate('forgot_password');
            $priority = $data['body']->stpl_priority;
            $data['body'] = str_replace('{name}', $admin->admin_name, $data['body']->stpl_body);
            $data['body'] = str_replace('{verificationCode}', $verificationCode, $data['body']);
            $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
            $notificationData['sms'] = [
                'message' => $data['body'],
                'recipients' => array('+' . $countryPhoneCode . $admin->admin_phone)
            ];
            dispatch(new SendNotification($notificationData, false, false, true))->onQueue($priority);
        }
    }

    public function forgotPassword(Request $request)
    {
        $smsPackageCheck = Package::getPublishedPackages('sms');
        $smsActivePackage = $smsPackageCheck->count();
        if (!empty($_POST)) {
            $rules = $messages = [];
            $via = 'email';
            if (array_key_exists("user_phone", $request->all())) {
                $rules['user_phone'] = 'required|numeric';
                $messages['user_phone.required'] = __('MSG_PHONE_NUMBER_REQURIED');
                $messages['user_phone.numeric'] = __('MSG_PHONE_NUMBER_NUMERIC');
                $via = 'phone';
            }
            if (array_key_exists("user_email", $request->all())) {
                $rules['user_email'] = 'required|email';
            }

            $this->validate($request, $rules, $messages);

            if (array_key_exists("user_phone", $request->all())) {
                $user = User::where('user_phone', $request->input('user_phone'))->where('user_is_guest', config("constants.NO"))->first();
                if (empty($user->user_id)) {
                    return back()->withInput($request->only('user_phone'))->withErrors(['user_phone' => __('MSG_PHONE_NUMBER_INCORRECT')]);
                } elseif ($user->user_publish == config('constants.NO')) {
                    return back()->withInput($request->only('user_phone'))->withErrors(['user_phone' => __('NOTI_ACCOUNT_DISABLED')]);
                }
            } else {
                $user = User::where('user_email', $request->input('user_email'))->where('user_is_guest', config("constants.NO"))->first();
                if (empty($user->user_id)) {
                    return back()->withInput($request->only('user_email'))->withErrors(['user_email' => __('MSG_EMAIL_NOT_EXIST')]);
                } elseif ($user->user_publish == config('constants.NO')) {
                    return back()->withInput($request->only('user_email'))->withErrors(['user_email' => __('NOTI_ACCOUNT_DISABLED')]);
                }
            }
            return redirect('password/reset/' . Crypt::encrypt($user->user_id) . '/' . $via)->with('sendCode', 1);
        }
        $defaultCountry = getDefaultCountryCode();
        return view('themes.' . config('theme') . '.forgotPassword', compact('smsActivePackage', 'defaultCountry'));
    }
}
