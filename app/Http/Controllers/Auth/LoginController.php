<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\YokartController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminAuthToken;
use App\Models\UserAuthToken;
use App\Models\User;
use App\Models\Country;
use App\Models\Configuration;
use App\Models\Package;
use Illuminate\Http\Request;
use Auth;
use Cookie;
use Carbon\Carbon;
use Crypt;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Socialite;
use Exception;
use App\Models\UserCart;
use App\Helpers\SocialHelper;
use App\Helpers\EmailHelper;
use GuzzleHttp\Client;
use Hash;
use Session;
use App\Events\SystemNotification;
use App\Models\SmsTemplate;
use App\Models\NotificationTemplate;
use App\Jobs\SendNotification;
use App\Models\Notification;
use App\Models\ShareEarnRecord;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class LoginController extends YokartController
{
    use ThrottlesLogins;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest:admin')->except('adminLogout');
        $this->middleware('guest')->except('logout', 'login', 'adminLogout', 'showLoginForm');
        $this->middleware('guest')->except('logout');
    }

    public function showAdminLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin');
        }
        return view('admin.auth.login');
    }

    public function adminLogin(Request $request)
    {
        $rememberMe = $request->input('remember');
        if (Auth::guard('admin')->attempt(
            ['admin_email' => $request->username, 'password' => $request->password, 'admin_publish' => 1]
        ) || Auth::guard('admin')->attempt(
            ['admin_username' => $request->username, 'password' => $request->password, 'admin_publish' => 1]
        )) {
            if (!empty($rememberMe)) {
                $this->setRememberMeToken($request);
            }
            if (Configuration::getValue('GETSTARTED_SKIP') == 0) {
                $statuses = Admin::moduleStats();
                if ($statuses['sum'] < 9) {
                    return jsonresponse(true, __('NOTI_LOGIN_SUCCESSFULLY'), ['url' => '#/get-started']);
                } else {
                    Configuration::saveValue('GETSTARTED_SKIP', 1);
                }
            }
            return jsonresponse(true, __('NOTI_LOGIN_SUCCESSFULLY'), ['url' => '#/']);
        }
        $errors = Admin::handleLoginErrors($request->username, $request->password);
        return jsonresponse(false, '', $errors);
    }

    public function adminLogout(Request $request)
    {
        $cookieToken = $this->retrieveCookie('RememberAdmin');
        AdminAuthToken::where('admauth_token', $cookieToken)->delete();
        Auth::guard('admin')->logout();
        return redirect('/admin/login')->withCookie($this->removeCookie('RememberAdmin'));
    }

    public function setRememberMeToken($request)
    {
        AdminAuthToken::where('admauth_admin_id', Auth::guard('admin')->user()->admin_id)->delete();
        $token = Str::random(32);
        AdminAuthToken::insert([
            'admauth_admin_id' => Auth::guard('admin')->user()->admin_id,
            'admauth_token' => $token,
            'admauth_expiry' => Carbon::now(),
            'admauth_browser' => $request->header('User-Agent'),
            'admauth_last_access' => Carbon::now(),
            'admauth_last_ip' => $request->ip(),
        ]);
        $this->makeCookie('RememberAdmin', $token);
    }

    public function logout(Request $request)
    {
        $cookieToken = $this->retrieveCookie('RememberUser');
        UserAuthToken::where('usrauth_token', $cookieToken)->delete();
        Auth::logout();
        $token = Str::random(32);

        $request->session()->forget('cartSession');
        $request->session()->put('_token', $token);
        $request->session()->forget('currency');
        $request->session()->forget('language');
        
        return redirect('/')->withCookie($this->removeCookie('RememberUser'));
    }

    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            return redirect('/');
        }
        if ($request->inertia()) {
            return response('', 409)->header('X-Inertia-Location', url()->current());
        }
        $smsPackageCheck = Package::getPublishedPackages('sms');
        $smsActivePackage = $smsPackageCheck->count();

        $defaultCountry = Str::lower(getDefaultCountryCode());
        return view('themes.' . config('theme') . '.login', compact('smsActivePackage', 'defaultCountry'));
    }

    public function loginViaForm(Request $request, $via)
    {
        $defaultCountry = Str::lower(getDefaultCountryCode());

        $smsPackageCheck = Package::getPublishedPackages('sms');
        $smsActivePackage = $smsPackageCheck->count();
        return view('themes.' . config('theme') . '.partials.loginVia', compact('defaultCountry', 'via', 'smsActivePackage'))->render();
    }

    public function frontendLogin(Request $request)
    {
        $loginData = [
            'user_publish' => 1,
            'password' => $request->password
        ];
        $rules = [
            'password' => 'required'
        ];
        if ($request->input('via') == "email") {
            $rules['username'] = 'required';
            $loginData['user_email'] = $request->username;
            $loginData['user_email_verified'] = 1;
        } else {
            $rules['user_phone'] = 'required';
            $loginData['user_phone'] = $request->user_phone;
            $country = Country::getCountries(['country_id'], ['country_code' => strtoupper($request->input('user_phone_country_code'))])->first();
            $request['user_phone_country_id'] = $loginData['user_phone_country_id'] = ($country->country_id ?? 0);
            $loginData['user_phone_verified'] = 1;
        }
        $this->validate($request, $rules, [], [
            'username' => 'email',
            'user_phone' => 'phone'
        ]);
        $rememberMe = $request->input('remember');
        if (Auth::attempt($loginData)) {
            if (!empty($rememberMe)) {
                $this->setFrontendRememberMeToken($request);
            }
            UserCart::updateCartItems();
            $acceptCookieVal = $this->retrieveCookie('AcceptCookie');
            if (empty(Auth::user()->user_cookie_preferences) && !empty($acceptCookieVal)) {
                User::where('user_id', Auth::user()->user_id)->update(["user_cookie_preferences" => $acceptCookieVal]);
                Auth::user()->user_cookie_preferences = $acceptCookieVal;
            }
            if ($request->ajax()) {
                return jsonresponse(true, '');
            }
            if (str_replace(url('/'), '', url()->previous()) != '/login') {
                return redirect()->back();
            }
            $request->session()->forget('currency');
            $request->session()->flash('loginSuccess', 1);
            return redirect('/');
        }
        $username = $request->username;
        $userDataq = User::select('user_id', 'user_password', 'user_publish', 'user_email', 'user_email_verified', 'user_phone_country_id', 'user_phone', 'user_phone_verified', 'user_is_guest');
        if ($request->input('via') == "email") {
            $userDataq->where('user_email', $username);
        } else {
            $userDataq->where('user_phone_country_id', $request['user_phone_country_id']);
            $userDataq->where('user_phone', $request->user_phone);
        }
        $userData = $userDataq->first();
        $smsPackage = Package::getActivePackage('sms');

        $message = __("NOTI_CREDENTIALS_DO_NOT_MATCH");
        if (!empty($userData->user_id) && Hash::check($request->password, $userData->user_password, []) && $userData->user_publish == 0) {
            $message = __("NOTI_ACCOUNT_DISABLED");
        }

        if (
            !empty($userData->user_id) &&
            Hash::check($request->password, $userData->user_password, []) &&
            (
                ($request->input('via') == "email" && !empty($userData->user_email) && $userData->user_email_verified == 0) ||
                ($request->input('via') == "phone" && !empty($userData->user_phone) && ($userData->user_phone_country_id == $country->country_id) && $userData->user_phone_verified == 0 && !empty($smsPackage))
            )
            && $userData->user_is_guest == config("constants.NO")
            && $userData->user_publish == config("constants.YES")
        ) {
            $userId = Crypt::encrypt($userData->user_id);
            $cookie = $this->makeCookie('Register', $userId, [15, null, null, false, false]);
            $url = '/account/verification/' . $userId . '/' . $request->input('via');
            if ($request->ajax()) {
                $request->session()->flash('sendCode', 1);
                return jsonresponse(false, $message, ['url' => $url]);
            } else {
                return redirect($url)->with('sendCode', 1);
            }
        }

        if ($request->ajax()) {
            return jsonresponse(false, $message);
        }

        toastr()->error($message);
        return back()->withInput($request->only('username', 'remember'));
    }

    public function setFrontendRememberMeToken($request)
    {
        $token = Str::random(32);
        UserAuthToken::where('usrauth_user_id', Auth::user()->user_id)->delete();
        UserAuthToken::insert([
            'usrauth_user_id' => Auth::user()->user_id,
            'usrauth_token' => $token,
            'usrauth_expiry' => Carbon::now(),
            'usrauth_browser' => $request->header('User-Agent'),
            'usrauth_last_access' => Carbon::now(),
            'usrauth_last_ip' => $request->ip(),
        ]);
        $this->makeCookie('RememberUser', $token);
    }

    public function redirect($service)
    {
        SocialHelper::initSocialite();
        $socialite = Socialite::driver($service);
        if ($service == 'instagram') {
            $socialite->setScopes(['user_profile', 'user_media']);
        }
        if (!empty(request()->get('referralcode'))) {
            Session::put('referralcode', request()->get('referralcode'));
        }
        return $socialite->redirect();
    }
    
    public function instagramProviderCallback(Request $request)
    {
        $referralcode = Session::get('referralcode');
        Session::forget('referralcode');
        $code = $request->code;
        if (empty($code)) {
            return redirect('/')->with('error', __('NOTI_SOMETHING_WENT_WRONG'));
        }

        SocialHelper::initSocialite();

        $client = new Client();
        // Get access token
        $response = $client->request('POST', 'https://api.instagram.com/oauth/access_token', [
            'form_params' => [
                'app_id' => config('services.instagram.client_id'),
                'app_secret' => config('services.instagram.client_secret'),
                'grant_type' => 'authorization_code',
                'redirect_uri' => config('services.instagram.redirect'),
                'code' => $code,
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            return redirect('/')->with('error', __("NOTI_SOMETHING_WENT_WRONG"));
        }

        $content = $response->getBody()->getContents();
        $content = json_decode($content);
        $accessToken = $content->access_token;
        $userId = $content->user_id;

        // Get user info
        $response = $client->request('GET', "https://graph.instagram.com/me?fields=id,username&access_token={$accessToken}");

        $content = $response->getBody()->getContents();
        $oAuth = json_decode($content);
        // dd($oAuth);
        // Get instagram user name
        $socialId = $oAuth->id;
        $socialName = $oAuth->username;
        $socialEmail = '';
        $socialUser = User::select('user_id', 'user_email', 'user_phone', 'user_email_verified', 'user_phone_verified', 'user_gdpr_approved')->where('user_instagram_id', $socialId)->first();

        if (empty($socialUser)) {
            $socialUser = new User;
            $socialUser->fill([
                'user_instagram_id' => $socialId,
                'user_fname' => $socialName,
                'user_email' => null
            ]);
            $socialUser->save();

            if (!empty($referralcode)) {
                ShareEarnRecord::addRewardPoints($referralcode, $socialUser->user_id);
                User::where('user_id', $socialUser->user_id)->update(['user_referral_code' => $referralcode]);
            }

            /** trigger event for system notification */
            $data = NotificationTemplate::getBySlug('new_user_registered');
            $message = str_replace('{email}', ($socialEmail) ? $socialEmail : $socialName, $data->ntpl_body);
            $message = str_replace('{userName}', $socialName, $message);
            event(new SystemNotification([
                'type' => Notification::USER_REGISTER,
                'record_id' => $socialUser->user_id,
                'from_id' => $socialUser->user_id,
                'message' => $message
            ]));
        } else {
            $socialEmail = $socialUser->user_email;
        }
        if (empty($socialEmail) && empty($socialUser->user_phone)) {
            return redirect('/additional-details/instagram/' . Crypt::encrypt($socialId));
        }
        if (!empty($socialEmail) && ($socialUser->user_gdpr_approved == 1)) {
            toastr()->error(__("you already submitted gdpr request so you can't login this email"));
            return redirect('/');
        }
        if ((!empty($socialEmail) && $socialUser->user_email_verified == 0) || (!empty($socialUser->user_phone) && $socialUser->user_phone_verified == 0)) {
            return redirect('/additional-details/verification/' . Crypt::encrypt($socialUser->user_id))->with('sendCode', 1);
        }

        Auth::loginUsingId($socialUser->user_id);
        if (Auth::check()) {
            $acceptCookieVal = $this->retrieveCookie('AcceptCookie');
            if (empty(Auth::user()->user_cookie_preferences) && !empty($acceptCookieVal)) {
                User::where('user_id', Auth::user()->user_id)->update(["user_cookie_preferences" => $acceptCookieVal]);
                Auth::user()->user_cookie_preferences = $acceptCookieVal;
            }

            $request->session()->forget('currency');
            $request->session()->flash('loginSuccess', 1);
            return redirect('/');
        }
    }

    public function callback($service)
    {
        $referralcode = Session::get('referralcode');
        Session::forget('referralcode');
        SocialHelper::initSocialite();
        try {
            $user = Socialite::with($service)->user();
            if (!empty($service) && in_array($service, ['facebook', 'google']) && !empty($user)) {
                $socialId = $user->getId();
                $socialName = $user->getName();
                $socialEmail = $user->getEmail();
                //$socialAvatar = $user->getAvatar();

                $socialUser = User::select('user_id', 'user_email', 'user_phone', 'user_email_verified', 'user_phone_verified', 'user_gdpr_approved')->where('user_' . $service . '_id', $socialId)->first();
                if (empty($socialUser) && !empty($socialEmail)) {
                    $socialUser = User::select('user_id', 'user_email', 'user_phone', 'user_email_verified', 'user_phone_verified')->where('user_email', $socialEmail)->first();
                    if (!empty($socialUser)) {
                        User::where('user_id', $socialUser->user_id)->update([
                            'user_' . $service . '_id' => $socialId,
                        ]);
                    }
                }

                if (empty($socialUser)) {
                    $socialUser = new User;
                    $explodedName = explode(' ', $socialName);
                    $socialData = [
                        'user_' . $service . '_id' => $socialId,
                        'user_fname' => ($explodedName[0] ?? ''),
                        'user_lname' => ($explodedName[1] ?? ''),
                        'user_email' => ($socialEmail) ? $socialEmail : null
                    ];
                    if (!empty($socialData['user_email'])) {
                        $socialData['user_email_verified'] = 1;
                    }
                    $socialUser->fill($socialData);
                    $socialUser->save();

                    if (!empty($referralcode)) {
                        ShareEarnRecord::addRewardPoints($referralcode, $socialUser->user_id);
                        User::where('user_id', $socialUser->user_id)->update(['user_referral_code' => $referralcode]);
                    }

                    /** trigger event for system notification */
                    $data = NotificationTemplate::getBySlug('new_user_registered');
                    $message = str_replace('{email}', ($socialEmail) ? $socialEmail : $socialName, $data->ntpl_body);
                    $message = str_replace('{userName}', $socialName, $message);
                    event(new SystemNotification([
                        'type' => Notification::USER_REGISTER,
                        'record_id' => $socialUser->user_id,
                        'from_id' => $socialUser->user_id,
                        'message' => $message
                    ]));
                } else {
                    $socialEmail = $socialUser->user_email;
                }
                if (empty($socialEmail) && empty($socialUser->user_phone)) {
                    return redirect('/additional-details/' . $service . '/' . Crypt::encrypt($socialId));
                }
                if (!empty($socialEmail) && ($socialUser->user_gdpr_approved == 1)) {
                    toastr()->error(__("you already submitted gdpr request so you can't login this email"));
                    return redirect('/');
                }
                if ((!empty($socialEmail) && $socialUser->user_email_verified == 0) || (!empty($socialUser->user_phone) && $socialUser->user_phone_verified == 0)) {
                    return redirect('/additional-details/verification/' . Crypt::encrypt($socialUser->user_id))->with('sendCode', 1);
                }
                Auth::loginUsingId($socialUser->user_id);
                if (Auth::check()) {
                    $acceptCookieVal = $this->retrieveCookie('AcceptCookie');
                    if (empty(Auth::user()->user_cookie_preferences) && !empty($acceptCookieVal)) {
                        User::where('user_id', Auth::user()->user_id)->update(["user_cookie_preferences" => $acceptCookieVal]);
                        Auth::user()->user_cookie_preferences = $acceptCookieVal;
                    }
                    $request->session()->forget('currency');
                    $request->session()->flash('loginSuccess', 1);
                    return redirect('/');
                }
            }
        } catch (Exception $e) {
        }
        return redirect('/');
    }

    public function enterAdditionalDetails(Request $request, $serviceType, $socialId)
    {
        $smsPackageCheck = Package::getPublishedPackages('sms');
        $smsActivePackage = $smsPackageCheck->count();

        $defaultCountry = Str::lower(getDefaultCountryCode());

        return view('themes.' . config('theme') . '.socialLoginAdditionalDetails', compact('smsActivePackage', 'serviceType', 'socialId', 'defaultCountry'));
    }

    public function saveAdditionalDetails(Request $request)
    {
        $userData = [];
        if (array_key_exists("user_phone", $request->all())) {
            $rules['user_phone'] = 'required|numeric|unique:users,user_phone';
            $userData['user_phone'] = $request->input('user_phone');
        }
        if (array_key_exists("user_email", $request->all())) {
            $rules['user_email'] = 'required|email|unique:users,user_email';
            $userData['user_email'] = $request->input('user_email');
        }
        $validate =  $this->validate($request, $rules, [], [
            'user_phone' => 'phone',
            'user_email' => 'email'
        ]);

        if ($request->input('socialId') && !empty($request->input('serviceType'))) {
            $decryptedSocialId = Crypt::decrypt($request->input('socialId'));
            $socialUser = User::select('user_id')->where('user_' . $request->input('serviceType') . '_id', $decryptedSocialId)->first();
            if (isset($socialUser) && !empty($socialUser)) {
                if (array_key_exists("user_phone", $request->all())) {
                    if (empty($request['user_phone_country_code'])) {
                        $request['user_phone_country_code'] = 'US';
                    }
                    $country = Country::getCountries(['country_id'], ['country_code' => strtoupper($request['user_phone_country_code'])])->first();
                    if (!empty($country->country_id)) {
                        $userData['user_phone_country_id'] = $country->country_id;
                    }
                }

                $socialUser->update($userData);

                return redirect('additional-details/verification/' . Crypt::encrypt($socialUser->user_id))->with('sendCode', 1);
            }
        }
    }

    /* social additional info verification start */
    public function additionalDetailsSuccessPage(Request $request, $slug)
    {
        $decryptedUserId = Crypt::decrypt($slug);
        $user = User::select('user_id', 'user_phone_verified', 'user_email_verified', 'user_email', 'user_phone_country_id', 'user_phone')
            ->where('user_id', $decryptedUserId)
            ->first();
        if (empty($user)) {
            abort(404);
        }

        $step = '';
        $sendCode = Session::get('sendCode');
        if (!empty($user->user_email) && $user->user_email_verified == 0) {
            $step = 'email-verify';
            if (!empty($sendCode) && $sendCode == 1) {
                $this->sendCodeOnEmail($user);
            }
        } elseif (!empty($user->user_phone)) {
            $smsPackage = Package::getActivePackage('sms');
            if (!empty($smsPackage)) {
                $step = 'phone-verify';
                $country = Country::getCountries(['country_phonecode'], ['country_id' => $user->user_phone_country_id])->first();
                $user->user_phone = '+' . $country->country_phonecode . $user->user_phone;
                if (!empty($sendCode) && $sendCode == 1) {
                    $this->sendCodeOnPhone($user, $country->country_phonecode);
                }
            }
        }

        return view('themes.' . config('theme') . '.additionalDetailsSuccess', compact('user', 'step', 'slug'));
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
            if (!empty($user->user_email) && $user->user_email_verified == 0) {
                $updateData['user_email_verified'] = 1;
            } elseif (!empty($user->user_phone)) {
                $updateData['user_phone_verified'] = 1;
            }
            User::where('user_id', $decryptedUserId)->update($updateData);
            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();

            return jsonresponse(true, __('NOTI_OTP_VERIFIED'), ['userId' => $user->user_id]);
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

        $type = str_replace('{shopUrl}', '<div class="btn-wrapper" style="text-align: center;">
        <a href="' . route('productListing') . '" style="border-radius: 4px;background-color: #f13925;color:#fff;font-size: 14px;letter-spacing: -0.28px;text-decoration:none;padding: 9px 20px;display: inline-block;margin-bottom: 30px;">Shop Now</a>
        </div>', $type);
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
        $data = SmsTemplate::getSMSTemplate('verify_phone');
        $priority = $data['body']->stpl_priority;
        $data['body'] = str_replace('{name}', $user->user_fname . ' ' . $user->user_lname, $data['body']->stpl_body);
        $data['body'] = str_replace('{verificationCode}', $code, $data['body']);
        $notificationData['sms'] = [
            'message' => $data['body'],
            'recipients' => array('+' . $countryCode . $user->user_phone)
        ];
        dispatch(new SendNotification($notificationData, false, false, true))->onQueue($priority);
    }
    /* signup verification end */

    public function username()
    {
        return 'email';
    }
    public function impersonateSubAdmin($id)
    {
        Auth::guard('admin')->logout();
        Auth::guard('admin')->loginUsingId($id);
        return redirect('/admin');
    }
}
