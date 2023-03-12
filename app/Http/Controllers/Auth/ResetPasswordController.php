<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\YokartController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\Admin\AdminPasswordResetRequest;
use App\Models\Admin\Admin;
use App\Models\User;
use App\Models\UserPasswordReset;
use App\Models\UserAuthToken;
use App\Models\Country;
use App\Helpers\EmailHelper;
use App\Models\SmsTemplate;
use App\Jobs\SendNotification;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Crypt;
use Session;

class ResetPasswordController extends YokartController
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
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function adminShowResetForm(Request $request, $token = null)
    {
        $resetToken = AdminPasswordResetRequest::select('aprr_admin_id', 'aprr_expiry')->where('aprr_token', $token)->first();
        if (empty($resetToken)) {
            toastr()->error(__('NOTI_RESET_PASSWORD_LINK_INVALID'));
            return redirect()->route('adminLoginForm');
        } elseif (!empty($resetToken) && Carbon::parse($resetToken->aprr_expiry)->addMinutes(config('app.expiredToken')) < Carbon::now()) {
            toastr()->error(__('NOTI_RESET_PASSWORD_LINK_EXPIRED'));
            return redirect()->route('adminLoginForm');
        }
        $admin = Admin::select('admin_email')->where('admin_id', $resetToken->aprr_admin_id)->first();
        return view('admin.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $admin->admin_email, 'vuefalse' => true]
        );
    }
    public function adminReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'password' => 'required|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ], [
        'password.regex' => __('MSG_PASSWORD_VALIDATION')
        ]);
        $resetToken = AdminPasswordResetRequest::select('aprr_admin_id', 'aprr_expiry')->where(['aprr_token' => $request->input('token')])->first();
        if ($resetToken) {
            AdminPasswordResetRequest::where(['aprr_token' => $request->input('token')])->delete();
            if (Carbon::parse($resetToken->aprr_expiry)->addMinutes(config('app.expiredToken')) < Carbon::now()) {
                return jsonresponse(false, __('NOTI_RESET_PASSWORD_LINK_EXPIRED'));
            }
            Admin::where('admin_id', $resetToken->aprr_admin_id)->update(['admin_password' => Hash::make($request->input('password'))]);
            return jsonresponse(true, __('NOTI_PASSWORD_UPDATED'));
        }
    }

    public function showResetForm(Request $request, $token, $via)
    {
        $decryptedUserId = Crypt::decrypt($token);
        $user = User::where('user_id', $decryptedUserId)->first();
        if (empty($user)) {
            abort(404);
        }
        $sendCode = Session::get('sendCode');
        if (!empty($sendCode) && $sendCode == 1) {
            if ($via == 'email') {
                $this->sendCodeOnEmail($user);
            } elseif ($via == 'phone') {
                $country = Country::getCountries(['country_phonecode'], ['country_id' => $user->user_phone_country_id])->first();
                $this->sendCodeOnPhone($user, $country->country_phonecode);
            }
        }
        return view('themes.' . config('theme') . '.resetPassword')->with(
            ['token' => $token, 'user' => $user, 'via' => $via]
        );
    }

    public function resendVerificationCode(Request $request, $via)
    {
        UserAuthToken::where('usrauth_expiry', '<', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())->delete();
        $decryptedUserId = Crypt::decrypt($request->input('slug'));
        $user = User::where('user_id', $decryptedUserId)->first();
        
        $country = Country::getCountries(['country_phonecode'], ['country_id' => $user->user_phone_country_id])->first();
        if ($via == 'email') {
            $this->sendCodeOnEmail($user);
        } elseif ($via == 'phone') {
            $this->sendCodeOnPhone($user, $country->country_phonecode);
        }
        
        return jsonresponse(true, '');
    }

    public function verifyCode(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required',
        ], [
            'otp.required' => __('MSG_VERIFICATION_CODE_REQUIRED'),
        ]);
        $decryptedUserId = Crypt::decrypt($request->input('slug'));
        $user = User::where('user_id', $decryptedUserId)->first();
        
        $userAuthToken = UserAuthToken::where('usrauth_user_id', $user->user_id)
            ->where('usrauth_token', $request['otp'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
            
        if (!empty($userAuthToken)) {
          //  UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();
            return jsonresponse(true, __('NOTI_OTP_VERIFIED'));
        }
        return jsonresponse(false, __('NOTI_ENTER_CORRECT_OTP'));
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required',
            'token' => 'required',
            'password' => 'required|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ], [
            'password.regex' => __('MSG_PASSWORD_VALIDATION'),
            'otp.required' => __('MSG_VERIFICATION_CODE_REQUIRED'),
        ]);
        $decryptedUserId = Crypt::decrypt($request->input('token'));
        $user = User::where('user_id', $decryptedUserId)->first();
        $userAuthToken = UserAuthToken::where('usrauth_user_id', $user->user_id)
            ->where('usrauth_token', $request['otp'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
            
        if (!empty($userAuthToken)) {
            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();
            User::where('user_id', $decryptedUserId)->update(['user_password' => Hash::make($request->input('password'))]);
            toastr()->success(__('NOTI_PASSWORD_UPDATED'));
            return redirect()->route('login');
        }    
        toastr()->error(__('NOTI_ENTER_CORRECT_OTP'));
        return back();
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
    private function sendCodeOnPhone($user, $countryCode)
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
            
        /*send sms code*/
        $data = SmsTemplate::getSMSTemplate('forgot_password');
        $priority = $data['body']->stpl_priority;
        $data['body'] = str_replace('{name}', $user->user_fname . ' ' . $user->user_lname, $data['body']->stpl_body);
        $data['body'] = str_replace('{verificationCode}', $code, $data['body']);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $notificationData['sms'] = [
            'message' => $data['body'],
            'recipients' => array('+' . $countryCode . $user->user_phone)
        ];
        dispatch(new SendNotification($notificationData, false, false, true))->onQueue($priority);
    }
}
