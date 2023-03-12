<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Country;
use App\Models\Package;
use App\Models\UserAuthToken;
use App\Models\SmsTemplate;
use App\Models\UserCart;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use App\Helpers\SocialHelper;
use Socialite;
use Auth;
use DB;
use Arr;
use App\Events\SystemNotification;
use App\Models\NotificationTemplate;
use App\Models\Notification;
use App\Models\Currency;
use App\Models\RecentlyViewedProduct;
use GuzzleHttp\Client;

class LoginController extends YokartController
{
    // public function appleLogin(Request $request)
    // {
    //     return Socialite::driver("sign-in-with-apple")
    //         ->scopes(["name", "email"])
    //         ->redirect();
    // }
    public function appleCallback(Request $request)
    {
        // $user = Socialite::driver("sign-in-with-apple")
        //     ->user();
        //     dd($user);

        // if (isset($post['id_token'])) {
        //     $claims = explode('.', $post['id_token'])[1];
        //     $claims = json_decode(base64_decode($claims), true);
            
        //     $appleUserInfo = isset($post['user']) ? json_decode($post['user'], true) : false;
    
        //     $appleId = isset($claims['sub']) ? $claims['sub'] : '';
    
        //     if (false === $appleUserInfo) {
        //         if (!isset($claims['email'])) {
        //             $message = Labels::getLabel('MSG_UNABLE_TO_FETCH_USER_INFO', $this->siteLangId);
        //             $this->setErrorAndRedirect($message, true);
        //         }
        //         $email = $claims['email'];
        //     } else {
        //         $email = $appleUserInfo['email'];
        //     }
            
        //     $exp = explode("@", $email);
        //     $username = substr($exp[0], 0, 80) . rand();

        //     $userInfo = $this->doLogin($email, $username, $appleId, $userType);
        //     $this->redirectToDashboard($userInfo['user_preferred_dashboard']);
        // }
        
        // $_SESSION['appleSignIn']['state'] = bin2hex(random_bytes(5));
        // FatApp::redirectUser($this->apple->getRequestUri());
        
    }

    public function socialLogin(Request $request, $service)
    {
        $token = $request->input('token');
        if (empty($token)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_INVALID_REQUEST"));
        }
        if (empty($service) || !in_array($service, ['facebook', 'google', 'sign-in-with-apple'])) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_INVALID_REQUEST"));
        }
        SocialHelper::initSocialite();
        $user = Socialite::with($service)->userFromToken($token);
        if (empty($user)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_USER_DOES_NOT_EXIST"));
        }
        $socialId = $user->getId();
        $socialName = $user->getName();
        $socialEmail = $user->getEmail();

        if ($service === "sign-in-with-apple") {
            $service = "apple";
        }

        $socialUser = User::select('user_id', 'user_email', 'user_fname', 'user_lname', 'user_phone', 'user_phone_country_id', 'user_email_verified', 'user_phone_verified', 'user_gdpr_approved')->where('user_' . $service . '_id', $socialId)->first();
        if (empty($socialUser) && !empty($socialEmail)) {
            $socialUser = User::select('user_id', 'user_email', 'user_fname', 'user_lname', 'user_phone', 'user_phone_country_id', 'user_email_verified', 'user_phone_verified', 'user_gdpr_approved')->where('user_email', $socialEmail)->first();
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
                        'user_email' => ($socialEmail) ? $socialEmail : null,
                        'user_register_type' => User::USER_SOCIAL_REGISTER_TYPE
                    ];
            if (!empty($socialData['user_email'])) {
                $socialData['user_email_verified'] = 1;
            }
            $socialUser->fill($socialData);
            $socialUser->save();

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
        $response = [];
        $response['user_id'] = $socialUser->user_id;
        $response['data_required'] = (string) config("constants.NO");
        $response['verification_required'] = (string) config("constants.NO");
        if (empty($socialEmail) && empty($socialUser->user_phone)) {
            $response['data_required'] = (string) config("constants.YES");
            return apiresponse(config('constants.SUCCESS'), '', $response);
        }
        if (!empty($socialEmail) && ($socialUser->user_gdpr_approved == 1)) {
            return apiresponse(config('constants.ERROR'), __("you already submitted gdpr request so you can't login this email"));
        }
        if ((!empty($socialEmail) && $socialUser->user_email_verified == 0) || (!empty($socialUser->user_phone) && $socialUser->user_phone_verified == 0)) {
            $response['verification_required'] = (string) config("constants.YES");
            if (!empty($socialEmail) && $socialUser->user_email_verified == 0) {
                $message = appLang("NOTI_EMAIL_NOT_VERIFIED");
                $this->sendCodeOnEmail($socialUser);
                $response['user_verification_type'] = (string) config("constants.NO"); //email
            } else {
                $message = appLang("NOTI_PHONE_NOT_VERIFIED");
                $country = Country::getCountries(['country_phonecode'], ['country_id' => $socialUser->user_phone_country_id])->first();
                $socialUser->user_phone = '+' . $country->country_phonecode . $socialUser->user_phone;
                $this->sendCodeOnPhone($socialUser);
                $response['user_verification_type'] = (string) config("constants.YES"); //phone
            }
            return apiresponse(config('constants.SUCCESS'), $message, $response);
        }
        $token = $socialUser->createToken(env('APP_NAME') . ' Password Grant Client')->accessToken;
        $response = $this->getUserData($socialUser->user_id);
        $response['token'] = $token;
        UserCart::updateCartItemsForApp($this->cartSession, $socialUser->user_id);
        RecentlyViewedProduct::updateListing($socialUser->user_id, request()->header('sess-token'));
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    
    public function instagramSocialLogin(Request $request)
    {
        $accessToken = $request->input('token');
        if (empty($accessToken)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_INVALID_REQUEST"));
        }

        SocialHelper::initSocialite();

        $client = new Client();
        // Get user info
        $response = $client->request('GET', "https://graph.instagram.com/me?fields=id,username&access_token={$accessToken}");

        $content = $response->getBody()->getContents();
        $oAuth = json_decode($content);
        
        // Get instagram user name
        $socialId = $oAuth->id;
        $socialName = $oAuth->username;
        $socialEmail = '';
        $socialUser = User::select('user_id', 'user_email', 'user_fname', 'user_lname', 'user_phone', 'user_phone_country_id', 'user_email_verified', 'user_phone_verified', 'user_gdpr_approved')->where('user_instagram_id', $socialId)->first();

        if (empty($socialUser)) {
            $socialUser = new User;
            $socialUser->fill([
                'user_instagram_id' => $socialId,
                'user_fname' => $socialName,
                'user_register_type' => User::USER_SOCIAL_REGISTER_TYPE,
                'user_email' => null
            ]);
            $socialUser->save();

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
        $response = [];
        $response['user_id'] = $socialUser->user_id;
        $response['data_required'] = (string) config("constants.NO");
        $response['verification_required'] = (string) config("constants.NO");
        if (empty($socialEmail) && empty($socialUser->user_phone)) {
            $response['data_required'] = (string) config("constants.YES");
            return apiresponse(config('constants.SUCCESS'), '', $response);
        }
        if (!empty($socialEmail) && ($socialUser->user_gdpr_approved == 1)) {
            return apiresponse(config('constants.ERROR'), __("you already submitted gdpr request so you can't login this email"));
        }
        if ((!empty($socialEmail) && $socialUser->user_email_verified == 0) || (!empty($socialUser->user_phone) && $socialUser->user_phone_verified == 0)) {
            $response['verification_required'] = (string) config("constants.YES");
            if (!empty($socialEmail) && $socialUser->user_email_verified == 0) {
                $message = appLang("NOTI_EMAIL_NOT_VERIFIED");
                $this->sendCodeOnEmail($socialUser);
                $response['user_verification_type'] = (string) config("constants.NO"); //email
            } else {
                $message = appLang("NOTI_PHONE_NOT_VERIFIED");
                $country = Country::getCountries(['country_phonecode'], ['country_id' => $socialUser->user_phone_country_id])->first();
                $socialUser->user_phone = '+' . $country->country_phonecode . $socialUser->user_phone;
                $this->sendCodeOnPhone($socialUser);
                $response['user_verification_type'] = (string) config("constants.YES"); //phone
            }
            return apiresponse(config('constants.SUCCESS'), $message, $response);
        }
        $token = $socialUser->createToken(env('APP_NAME') . ' Password Grant Client')->accessToken;
        $response = $this->getUserData($socialUser->user_id);
        $response['token'] = $token;
        UserCart::updateCartItemsForApp($this->cartSession, $socialUser->user_id);
        RecentlyViewedProduct::updateListing($socialUser->user_id, request()->header('sess-token'));
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function saveAdditionalDetails(Request $request)
    {
        $userData = $rules = [];
        $rules['user_id'] = 'required';
        if (array_key_exists("phone", $request->all())) {
            $rules['phone'] = 'required|numeric|unique:users,user_phone';
            $userData['user_phone'] = $request->input('phone');
        }
        if (array_key_exists("user_email", $request->all())) {
            $rules['email'] = 'required|email|unique:users,user_email';
            $userData['user_email'] = $request->input('email');
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $response = [];
        $message = "";
        $user = User::select('user_id', 'user_fname', 'user_email', 'user_lname')->where('user_id', $request->input('user_id'))->first();
        if (array_key_exists("phone", $request->all())) {
            if (empty($request['phone_code'])) {
                $request['phone_code'] = 'US';
            }
            $country = Country::getCountries(['country_id', 'country_phonecode'], ['country_code' => strtoupper($request['phone_code'])])->first();
            if (!empty($country->country_id)) {
                $userData['user_phone_country_id'] = $country->country_id;
            }
            $response = [
                'user_id' => $user->user_id,
                'user_phone' => $request['phone'],
                'user_phone_code' => '+' . $country->country_phonecode
            ];
            $user->user_phone = '+' . $country->country_phonecode . $request['phone'];
            $this->sendCodeOnPhone($user);
            $message = appLang("NOTI_CODE_SENT_ON_PHONE");
        } else {
            $response = [
                'user_id' => $user->user_id,
                'user_email' => $user->user_email,
            ];
            $this->sendCodeOnEmail($user);
            $message = appLang("NOTI_CODE_SENT_ON_EMAIL");
        }
        $user->update($userData);

        return apiresponse(config('constants.SUCCESS'), $message, $response);
    }

    public function loginByEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $user = User::where('user_email', $request->email)
            ->where('user_email_verified', config('constants.YES'))
            ->where('user_publish', config('constants.YES'))
            ->first();
        if (empty($user)) {
            $message = "";
            $statusCode = config('constants.ERROR');
            $response = [
                'verification_required' => '0',
                'account_disabled' => '0'
            ];
            $user = User::select('user_id', 'user_fname', 'user_lname', 'user_email', 'user_publish', 'user_password', 'user_email_verified')
                ->where('user_email', $request->email)
                ->first();
            if (empty($user)) {
                $message = appLang("NOTI_USER_DOES_NOT_EXIST");
            } else {
                if (Hash::check($request->password, $user->user_password, [])) {
                    if ($user->user_publish === config('constants.NO')) {
                        $message = appLang("NOTI_ACCOUNT_DISABLED");
                        $response['account_disabled'] = "1";
                    } elseif ($user->user_email_verified === config('constants.NO')) {
                        $message = appLang("NOTI_EMAIL_NOT_VERIFIED");
                        $response['verification_required'] = "1";
                        $response['user_id'] = $user->user_id;
                        $statusCode = config('constants.SUCCESS');
                        $this->sendCodeOnEmail($user);
                    }
                } else {
                    $message = appLang("NOTI_CREDENTIALS_DO_NOT_MATCH");
                }
            }
            return apiresponse($statusCode, $message, $response);
        } else {
            if (Hash::check($request->password, $user->user_password)) {
                $token = $user->createToken(env('APP_NAME') . ' Password Grant Client')->accessToken;
                $response = $this->getUserData($user->user_id);
                $response['token'] = $token;
                UserCart::updateCartItemsForApp($this->cartSession, $user->user_id);
                RecentlyViewedProduct::updateListing($user->user_id, request()->header('sess-token'));
                return apiresponse(config('constants.SUCCESS'), '', $response);
            } else {
                return apiresponse(config('constants.ERROR'), appLang("NOTI_CREDENTIALS_DO_NOT_MATCH"));
            }
        }
    }

    public function resendCodeForSocial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $userId = $request->input('user_id');
        $type = $request->input('type');
        $user = User::select('user_id', 'user_email', 'user_fname', 'user_lname', 'user_phone', 'user_phone_country_id', 'user_email_verified', 'user_phone_verified')->where('user_id', $userId)->first();
        if (empty($user)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_USER_DOES_NOT_EXIST"));
        }
        if ($type == "1") {
            $country = Country::getCountries(['country_phonecode'], ['country_id' => $user->user_phone_country_id])->first();
            $response = [
                'user_id' => $user->user_id,
                'user_phone' => $user->user_phone,
                'user_phone_code' => '+' . $country->country_phonecode
            ];
            $user->user_phone = '+' . $country->country_phonecode . $user->user_phone;
            $this->sendCodeOnPhone($user);
            $message = appLang("NOTI_CODE_SENT_ON_PHONE");
        } else {
            $response = [
                'user_id' => $user->user_id,
                'user_email' => $user->user_email,
            ];
            $this->sendCodeOnEmail($user);
            $message = appLang("NOTI_CODE_SENT_ON_EMAIL");
        }
        return apiresponse(config('constants.SUCCESS'), $message, $response);
    }

    public function verifyCodeForSocial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'verification_code' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $type = $request->input('type');
        $userId = $request->input('user_id');
        $user = User::select('user_id', 'user_email_verified')->where('user_id', $userId)->first();

        $userAuthToken = UserAuthToken::where('usrauth_user_id', $user->user_id)
            ->where('usrauth_token', $request['verification_code'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
        if (!empty($userAuthToken)) {
            if ($type == "0" && $user->user_email_verified === config('constants.NO')) {
                User::where('user_id', $user->user_id)->update(['user_email_verified' => config('constants.YES')]);
            }
            if ($type == "1" && $user->user_phone_verified === config('constants.NO')) {
                User::where('user_id', $user->user_id)->update(['user_phone_verified' => config('constants.YES')]);
            }
            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();

            $token = $user->createToken(env('APP_NAME') . ' Password Grant Client')->accessToken;
            $response = $this->getUserData($user->user_id);
            $response['token'] = $token;
            UserCart::updateCartItemsForApp($this->cartSession, $user->user_id);
            RecentlyViewedProduct::updateListing($user->user_id, request()->header('sess-token'));
            return apiresponse(config('constants.SUCCESS'), appLang('NOTI_CODE_VERIFIED'), $response);
        }
        return apiresponse(config('constants.ERROR'), appLang('NOTI_ENTER_CORRECT_CODE'));
    }

    private function getUserData($userId)
    {
        return User::getDetailsForApp($userId);
    }

    public function resendCodeOnEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }

        $user = User::select('user_id', 'user_email')
            ->where('user_email', $request->email)
            ->first();
        if (empty($user)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_USER_DOES_NOT_EXIST"));
        }
        $response = [
            'user_id' => $user->user_id,
            'user_email' => $user->user_email,
        ];
        $this->sendCodeOnEmail($user);
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CODE_SENT_ON_EMAIL"), $response);
    }

    public function loginByEmailVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'verification_code' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $userId = $request->input('user_id');
        $user = User::select('user_id', 'user_email_verified')->where('user_id', $userId)->first();

        $userAuthToken = UserAuthToken::where('usrauth_user_id', $user->user_id)
            ->where('usrauth_token', $request['verification_code'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
        if (!empty($userAuthToken)) {
            if ($user->user_email_verified === config('constants.NO')) {
                User::where('user_id', $user->user_id)->update(['user_email_verified' => config('constants.YES')]);
            }
            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();

            $token = $user->createToken(env('APP_NAME') . ' Password Grant Client')->accessToken;
            $response = $this->getUserData($user->user_id);
            $response['token'] = $token;
            UserCart::updateCartItemsForApp($this->cartSession, $user->user_id);
            RecentlyViewedProduct::updateListing($user->user_id, request()->header('sess-token'));
            return apiresponse(config('constants.SUCCESS'), appLang('NOTI_CODE_VERIFIED'), $response);
        }
        return apiresponse(config('constants.ERROR'), appLang('NOTI_ENTER_CORRECT_CODE'));
    }

    public function loginByPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_code' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }

        $country = Country::getCountries(['country_id', 'country_phonecode'], ['country_code' => strtoupper($request->phone_code)])->first();

        $user = User::select('user_id', 'user_phone')
            ->where('user_phone', $request->phone)
            ->where('user_phone_country_id', $country->country_id)
            ->where('user_phone_verified', config('constants.YES'))
            ->where('user_publish', config('constants.YES'))
            ->first();

        if (empty($user)) {
            $message = "";
            $statusCode = config('constants.ERROR');
            $response = [
                'verification_required' => '0',
                'existing_user' => '0',
                'account_disabled' => '0'
            ];
            $user = User::select('user_id', 'user_fname', 'user_lname', 'user_phone', 'user_publish', 'user_phone_verified')
                ->where('user_phone', $request->phone)
                ->where('user_phone_country_id', $country->country_id)
                ->first();
            if (empty($user)) {
                $message = appLang("NOTI_PHONE_DO_NOT_MATCH");
            } else {
                if ($user->user_publish === config('constants.NO')) {
                    $message = appLang("NOTI_ACCOUNT_DISABLED");
                    $response['account_disabled'] = "1";
                } elseif ($user->user_phone_verified === config('constants.NO')) {
                    $message = appLang("NOTI_PHONE_NOT_VERIFIED");
                    $response['verification_required'] = "1";
                    $response['user_id'] = $user->user_id;
                    $statusCode = config('constants.SUCCESS');
                    $user->user_phone = '+' . $country->country_phonecode . $user->user_phone;
                    $this->sendCodeOnPhone($user);
                }
            }
            return apiresponse($statusCode, $message, $response);
        } else {
            $response = [
                'user_id' => $user->user_id,
                'existing_user' => 0,
                'user_phone' => $user->user_phone,
                'user_phone_code' => '+' . $country->country_phonecode
            ];
            $user->user_phone = '+' . $country->country_phonecode . $user->user_phone;
            $this->sendCodeOnPhone($user);
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CODE_SENT_ON_PHONE"), $response);
        }
    }

    public function resendCodeOnPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_code' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }

        $country = Country::getCountries(['country_id', 'country_phonecode'], ['country_code' => strtoupper($request->phone_code)])->first();

        $user = User::select('user_id', 'user_phone')
            ->where('user_phone', $request->phone)
            ->where('user_phone_country_id', $country->country_id)
            ->first();
        if (empty($user)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_CREDENTIALS_DO_NOT_MATCH"));
        }
        $response = [
            'user_id' => $user->user_id,
            'user_phone' => $user->user_phone,
            'user_phone_code' => '+' . $country->country_phonecode
        ];
        $user->user_phone = '+' . $country->country_phonecode . $user->user_phone;
        $this->sendCodeOnPhone($user);
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CODE_SENT_ON_PHONE"), $response);
    }
    
    public function loginByPhoneVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'verification_code' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $userId = $request->input('user_id');
        $user = User::select('user_id', 'user_fname', 'user_phone_verified')->where('user_id', $userId)->first();
      
        $userAuthToken = UserAuthToken::where('usrauth_user_id', $user->user_id)
            ->where('usrauth_token', $request['verification_code'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
            $infoRequired = 0;
        if (!empty($userAuthToken)) {
            if ($user->user_phone_verified === config('constants.NO')) {
                User::where('user_id', $user->user_id)->update(['user_phone_verified' => config('constants.YES')]);
            }
            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();
            if (empty($user->user_fname)) {
                $response['user_id'] = $userId;
                $infoRequired = 1;
            } else {
                $token = $user->createToken(env('APP_NAME') . ' Password Grant Client')->accessToken;
                $response = $this->getUserData($user->user_id);
                $response['token'] = $token;
                UserCart::updateCartItemsForApp($this->cartSession, $user->user_id);
                RecentlyViewedProduct::updateListing($user->user_id, request()->header('sess-token'));
            }
            $response['additional_info'] = $infoRequired;
            return apiresponse(config('constants.SUCCESS'), appLang('NOTI_CODE_VERIFIED'), $response);
        }
        return apiresponse(config('constants.ERROR'), appLang('NOTI_ENTER_CORRECT_CODE'));
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_LOGGEDOUT"));
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

    private function sendCodeOnPhone($user)
    {
        $smsPackageCheck = Package::getPublishedPackages('sms');
        if ($smsPackageCheck->count() == 0) {
            return false;
        }
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
            'recipients' => array($user->user_phone)
        ];
        dispatch(new SendNotification($notificationData, false, false, true))->onQueue($priority);
    }    
}
