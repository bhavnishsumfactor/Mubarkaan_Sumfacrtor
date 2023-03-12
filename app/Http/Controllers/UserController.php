<?php

namespace App\Http\Controllers;

use App\Http\Controllers\YokartController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Package;
use App\Models\UserAuthToken;
use App\Models\AttachedFile;
use App\Models\Country;
use App\Models\State;
use App\Models\Product;
use App\Models\Timezone;
use App\Models\Language;
use App\Models\Currency;
use App\Models\UsersRequestHistory;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\SmsTemplate;
use App\Http\Requests\UserLocalizationRequest;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserAddressRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePhoneRequest;
use App\Http\Requests\ChangePhoneDirectRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Auth;
use Crypt;
use Carbon\Carbon;
use App\Helpers\FileHelper;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use App\Events\SystemNotification;
use App\Models\NotificationTemplate;
use App\Models\Notification;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Redirect;
use Cache;
use File;
use PDF;
use App\Models\DiscountCouponRecord;

class UserController extends YokartController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    public function favorite(Request $request)
    {
        $aspectRatio = Product::PRODUCT_MEDIA_TYPE[getConfigValueByName('MEDIA_TYPES')];
        return Inertia::render('Activity/Favorite', ['shopUrl' => route('productListing'), 'aspectRatio' => $aspectRatio]);
    }
    public function favoriteListing(Request $request)
    {
        $products = Product::getFavoriteProductByUserId($this->user->user_id, $request->all(), $request->input('total'));
        return jsonresponse(true, null, $products);
    }
    public function updateImage(Request $request)
    {
        imageUpload($request, $this->user->user_id, 'userProfileImage');
        $imageURL =  getFileUrl('userProfileImage', $this->user->user_id);
        $thumbURL =  getFileUrl('userProfileImage', $this->user->user_id, 0, '100-100');
        if (empty($imageURL)) {
            $thumbURL =  $imageURL = noImage("1_1/100x100.png");
        }
        return jsonresponse(true, __('NOTI_USER_IMAGE_UPDATED'), ['imageURL' => $imageURL, 'thumbURL' => $thumbURL]);
    }

    public function updateRequestGdprData(Request $request)
    {
        $this->validateGdprRequest($request->all())->validate();
        $request['type'] = 'datarequest';
        if (UsersRequestHistory::saveUserGdprRequest($request->all(), $this->user->user_id)) {
            $content = UsersRequestHistory::getUserData($this->user->user_id);

            /** trigger event for system notification */
            $data = NotificationTemplate::getBySlug('gdpr_data_request');
            $message = str_replace('{userName}', Str::title($this->user->user_fname . ' ' . $this->user->user_lname), $data->ntpl_body);
            event(new SystemNotification([
                'type' => Notification::GDPR_DATA_REQUEST,
                'from_id' => $this->user->user_id,
                'message' => $message
            ]));

            /*send email code*/
            $data = EmailHelper::getEmailData('gdpr_data_requested');
            $priority = $data['body']->etpl_priority;
            $data['subject'] = $this->replaceGdprTags($data['body']->etpl_subject, $this->user);
            $data['body'] = $this->replaceGdprTags($data['body']->etpl_body, $this->user);
            $data['to'] = $this->user->user_email;

            $pdf = PDF::loadHTML($content);
            $filename = randomString(10) . ".pdf";
            if (!File::isDirectory('public/userdata')) {
                File::makeDirectory('public/userdata', 0777, true, true);
            }
            $pdf->save('public/userdata/' . $filename);
            $destinationPath = url('/userdata/' . $filename);

            $data['attachment'] = [
                [
                    'file' => $destinationPath,
                    'params' => [
                        'as' => 'gdpr_data.pdf'
                    ]
                ]
            ];
            dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);

            return jsonresponse(true, __('NOTI_GDPR_DATA_DOWNLOD'), $destinationPath);
        }
        return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
    }

    public function requestRemoveGdprData(Request $request, $userId)
    {
        if (UsersRequestHistory::removeUserData($userId)) {
            $request['type'] = 'deletionrequest';
            $request['ureq_user_id'] = $userId;
            $request['ureq_purpose'] = "";
            UsersRequestHistory::saveUserGdprRequest($request->all(), $userId);
            /** trigger event for system notification */
            $data = NotificationTemplate::getBySlug('gdpr_deletion_request');
            $message = str_replace('{userName}', Str::title($this->user->user_fname . ' ' . $this->user->user_lname), $data->ntpl_body);
            event(new SystemNotification([
                'type' => Notification::GDPR_DELETE_REQUEST,
                'from_id' => $this->user->user_id,
                'message' => $message
            ]));

            $notificationData = [];
            $sendSms = false;
            /*send email code*/
            $data = EmailHelper::getEmailData('gdpr_delete_account');
            $priority = $data['body']->etpl_priority;
            $data['subject'] = $this->replaceGdprTags($data['body']->etpl_subject, $this->user);
            $data['body'] = $this->replaceGdprTags($data['body']->etpl_body, $this->user);
            $data['to'] = $this->user->user_email;
            $notificationData['email'] = $data;

            $country = Country::select('country_phonecode')->where('country_id', $this->user->user_phone_country_id)->first();
            if (!empty($country->country_phonecode) && !empty($this->user->user_phone)) {
                $data = SmsTemplate::getSMSTemplate('gdpr_delete_account');
                $priority = $data['body']->stpl_priority;
                $data['body'] = str_replace('{name}', $this->user->user_fname . ' ' . $this->user->user_lname, $data['body']->stpl_body);
                $notificationData['sms'] = [
                    'message' => $data['body'],
                    'recipients' => array('+' . $country->country_phonecode . $this->user->user_phone)
                ];
                $sendSms = true;
            }
            
            dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);

            $cookieToken = $this->retrieveCookie('RememberUser');
            UserAuthToken::where('usrauth_token', $cookieToken)->delete();
            Auth::logout();
            return jsonresponse(true, __('NOTI_GDPR_DELETED'));
        }
        return jsonresponse(false, __('NOTI_GDPR_DELETE_ERROR'));
    }
    
    private function replaceGdprTags($content, $user)
    {
        $content = str_replace('{userName}', $user->user_fname . ' ' . $user->user_lname, $content);
        $content = str_replace('{contactEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $content);
        $content = str_replace('{websiteName}', (getConfigValueByName('BUSINESS_NAME') ?? ''), $content);
        return $content;
    }

    public function validateGdprRequest(array $data)
    {
        $validator = Validator::make($data, [
            'ureq_purpose' => ['required'],
        ]);
        return $validator->setAttributeNames([
            'ureq_purpose' => 'Purpose'
        ]);
    }

    public function profile()
    {
        $user = $this->user;
        $checkUserProfileImage = fileExists('userProfileImage', $this->user->user_id, 0);
        $phoneCode = '1';
        if (!empty($user->user_phone_country_id) && !empty($user->phoneCountry->country_code)) {
            $phoneCode = $user->phoneCountry->country_phonecode;
        } else {
            $currentCountryCode = Str::upper(getDefaultCountryCode());
            if (!empty($currentCountryCode)) {
                $country = Country::select('country_phonecode')->where('country_code', $currentCountryCode)->first();
                $phoneCode = $country->country_phonecode;
            }
        }
        $genders = User::GENDER;
        $smsPackageCheck = Package::getPublishedPackages('sms');
        return Inertia::render('Account/Profile', ['genders' => $genders, 'phoneCode' => $phoneCode, 'smsActivePackage' => $smsPackageCheck->count(), 'checkUserProfileImage' => $checkUserProfileImage]);
    }
    public function profileUpdate(UserProfileRequest $request)
    {
        User::where('user_id', $this->user->user_id)->update($request->only('user_fname', 'user_lname', 'user_dob', 'user_gender'));
        $user = User::select('user_id', 'user_email')->where('user_id', $this->user->user_id)->first();
        $message = __('NOTI_PROFILE_UPDATED');
        return jsonresponse(true, $message);
    }

    public function resendPhoneVerificationCode(Request $request)
    {
        $country = Country::getCountries(['country_id', 'country_phonecode'], ['country_code' => $request->input('user_phone_country_id')])->first();
        $phoneExists = User::where("user_phone", $request->input('phone'))->where('user_phone_country_id', $country->country_id)->get()->count();
        if ($phoneExists >= 1) {
            return jsonresponse(false, __('NOTI_PHONE_ALREADY_EXISTS'));
        }
        UserAuthToken::where('usrauth_expiry', '<', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())->delete();
        $user = User::where('user_id', $this->user->user_id)->first();
        $this->sendCodeOnPhone($user, $country->country_phonecode);
        return jsonresponse(true, __('NOTI_OTP_SEND_SUCCESSFULLY'));
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
        $data = SmsTemplate::getSMSTemplate('update_phone_verify');
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

    public function verifyUpdatedPhone(ChangePhoneRequest $request)
    {
        $user = User::where('user_id', $this->user->user_id)->first();

        $userAuthToken = UserAuthToken::where('usrauth_user_id', $user->user_id)
            ->where('usrauth_token', $request['code'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
        if (!empty($userAuthToken)) {
            if (!Hash::check($request['password'], $this->user->user_password)) {
                return jsonresponse(false, __('Password not Matched!!'), ['type' => 'password']);
            }
            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();
            if (empty($request['user_phone_country_code'])) {
                $request['user_phone_country_code'] = 'US';
            }
            $country = Country::getCountries(['country_id'], ['country_code' => strtoupper($request['user_phone_country_code'])])->first();
            if (!empty($country->country_id)) {
                $request['user_phone_country_id'] = $country->country_id;
            }
            User::where('user_id', $user->user_id)->update([
                'user_phone_country_id' => $request['user_phone_country_id'],
                'user_phone' => $request['user_phone'],
                'user_phone_verified' => config('constants.YES')
            ]);
            return jsonresponse(true, __('NOTI_PHONE_UPDATED'));
        }
        return jsonresponse(false, __('NOTI_VERIFICATION_CODE_INCORRECT'), ['type' => 'otp_unverified']);
    }

    public function updatePhoneDirectly(ChangePhoneDirectRequest $request)
    {
        $user = User::where('user_id', $this->user->user_id)->first();

        if (empty($request['user_phone_country_code'])) {
            $request['user_phone_country_code'] = 'US';
        }
        $country = Country::getCountries(['country_id'], ['country_code' => strtoupper($request['user_phone_country_code'])])->first();
        if (!empty($country->country_id)) {
            $request['user_phone_country_id'] = $country->country_id;
        }

        User::where('user_id', $user->user_id)->update([
            'user_phone_country_id' => $request['user_phone_country_id'],
            'user_phone' => $request['user_phone'],
            'user_phone_verified' => config('constants.YES')
        ]);
        return jsonresponse(true, __('NOTI_PHONE_UPDATED'));
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        if ($request->input('new_password') != $request->input('confirm_password')) {
            return jsonresponse(false, '', ['errors' => ['confirm_password' => [__('Confirm password should match new password')]]]);
        }

        $data['user_password'] = Hash::make($request->input('new_password'));
        User::where('user_id', $this->user->user_id)->update($data);
        return jsonresponse(true, __('NOTI_PASSWORD_UPDATED'));
    }

    public function resendEmailVerificationCode(Request $request)
    {
        $user = User::whereRaw("lower(`user_email`) LIKE ?", [$request->input('new_email')])->get()->count();
        if ($user >= 1) {
            return jsonresponse(false, __('NOTI_EMAIL_ALREADY_EXISTS'));
        }
        UserAuthToken::where('usrauth_expiry', '<', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())->delete();
        $user = User::where('user_id', $this->user->user_id)->first();
        $this->sendCodeOnEmail($user);
        return jsonresponse(true, __('NOTI_OTP_SEND_SUCCESSFULLY'));
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
        $data = EmailHelper::getEmailData('update_email_verify');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $this->replacementTags($data['body']->etpl_subject, $user, $code);
        $data['body'] = $this->replacementTags($data['body']->etpl_body, $user, $code);
        $data['to'] = $user->user_email;
        dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
    }

    private function replacementTags($type, $user, $code)
    {
        $type = str_replace('{name}', $user->user_fname . ' ' . $user->user_lname, $type);
        $type = str_replace('{verificationCode}', $code, $type);
        $type = str_replace('{contactUsEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $type);
        $type = str_replace('{websiteName}', env('APP_NAME'), $type);
        return $type;
    }

    public function verifyUpdatedEmail(ChangeEmailRequest $request)
    {
        $userAuthToken = UserAuthToken::where('usrauth_user_id', $this->user->user_id)
            ->where('usrauth_token', $request['code'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
        if (!empty($userAuthToken)) {
            UserAuthToken::where('usrauth_user_id', $this->user->user_id)->delete();
            User::where('user_id', $this->user->user_id)->update([
                'user_email' => $request->input('email'),
                'user_email_verified' => 1
            ]);
            return jsonresponse(true, __('NOTI_EMAIL_UPDATED'));
        }
        return jsonresponse(false, __('NOTI_VERIFICATION_CODE_INCORRECT'), ['type' => 'otp_unverified']);
    }

    public function localization()
    {
        $languages = Language::getLanguages(['lang_name', 'lang_id'], ['lang_publish' => config('constants.YES')])->pluck('lang_name', 'lang_id');
        $currencies = Currency::getCurrencies(['currency_name', 'currency_id', 'currency_code'], ['currency_publish' => config('constants.YES')]);
        $countries = Country::getCountries(['country_name', 'country_id'])->pluck('country_name', 'country_id');
        $timezones = Timezone::getAllTimeZones();

        return Inertia::render('Account/Localization', ['languages' => $languages, 'currencies' => $currencies, 'countries' => $countries, 'timezones' => $timezones]);
    }

    public function localizationUpdate(UserLocalizationRequest $request)
    {
        $result = $request->only('user_country_id', 'user_language_id', 'user_currency_id', 'user_timezone');
        if (empty($result['user_currency_id'])) {
            unset($result['user_currency_id']);
        }
        if (empty($result['user_language_id']) || $result['user_language_id'] == 'null') {
            unset($result['user_language_id']);
        }
        User::where('user_id', $this->user->user_id)->update($result);
        if ($request['user_currency_id'] != 'null' && !empty($request['user_currency_id']) && $this->user->user_currency_id != $request['user_currency_id']) {
            $this->user->user_currency_id = $request['user_currency_id'];
            $request->session()->forget('currency');
        }
        if ($request['user_language_id'] != 'null' && !empty($request['user_language_id']) && $this->user->user_language_id != $request['user_language_id']) {
            $language = Language::getRecordById($request['user_language_id']);
            $this->user->user_language_id = $request['user_language_id'];
            $request->session()->forget('language');
            Session::put('appdashboardLocale', $language->lang_code);
            \Cache::forget('user-lang-'.$this->user->user_id.'.js');
        }
        return jsonresponse(true, __('NOTI_LOCALIZATION_UPDATED'));
    }

    public function cookiePreferences()
    {
        if (getConfigValueByName('ACCEPT_COOKIE_ENABLE') == 0) {
            abort(404);
        }
        if (!empty($this->user->user_cookie_preferences)) {
            $settings = unserialize($this->user->user_cookie_preferences);
        } else {
            $settings = [
                'functional' => "1",
                'analytics' => "1",
                'personalized' => "1",
                'advertising' => "1",
            ];
        }
        return Inertia::render('Account/CookiePreferences', ['settings' => $settings]);
    }

    public function cookiePreferencesUpdate(Request $request)
    {
        $requestData = $request->all();
        $serialized = serialize([
            'functional' => "1",
            'analytics' => $requestData['analytics'],
            'personalized' => $requestData['personalized'],
            'advertising' => $requestData['advertising']
        ]);
        User::where('user_id', $this->user->user_id)->update(["user_cookie_preferences" => $serialized]);
        $this->user->user_cookie_preferences = $serialized;
        return jsonresponse(true, __('NOTI_LOCALIZATION_UPDATED'));
    }

    public function coupons(Request $request)
    {
        $totalSaving = Order::where('order_user_id', $this->user->user_id)->sum('order_discount_amount');
        return Inertia::render('Activity/DiscountCoupons', ['totalDiscount' => $totalSaving, 'shopUrl' => route('productListing')]);
    }
    public function couponListing()
    {
        $records = DiscountCoupon::getCoupons($this->user->user_id);
        return jsonresponse(true, '', $records);
    }
    public function loadMoreCoupons(Request $request)
    {
        $records = DiscountCoupon::getCoupons($this->user->user_id, $request->input('total'));
        return jsonresponse(true, '', $records);
    }
    public function getIncluded(Request $request)
    {
        $data = [];
        $data = DiscountCoupon::getCouponLinkedData($request->input('id'));
        return jsonresponse(true, null, $data);
    }
    public function address()
    {
        return Inertia::render('Account/Address/List');
    }
    public function addressListing()
    {
        $records = UserAddress::getAddresses($this->user->user_id);
        return jsonresponse(true, '', $records);
    }
    public function loadMoreAddresses(Request $request)
    {
        $records = UserAddress::getAddresses($this->user->user_id, $request->input('total'));
        return jsonresponse(true, '', $records);
    }

    public function editAddress(Request $request, $id)
    {
        $address = UserAddress::getAddressByid($id);
        $states = State::where('state_country_id', $address->addr_country_id)->pluck('state_name', 'state_id');
        $countries = Country::all();
        return Inertia::render('Account/Address/Form', ['countries' => $countries, 'states' => $states, 'defaultCountryId' => getDefaultCountryCode(), 'editData' => $address]);
    }

    public function addAddress(Request $request)
    {
        $countries = Country::all();
        $defaultCountryId = $countries->first()->country_id;

        $states = State::where('state_country_id', $defaultCountryId)->pluck('state_name', 'state_id');
        return Inertia::render('Account/Address/Form', ['countries' => $countries, 'states' => $states, 'defaultCountry' => getDefaultCountryCode(), 'defaultCountryId' => $defaultCountryId, 'editData' => '']);
    }

    public function addressInfo(UserAddressRequest $request)
    {
        $lastId = $request->input('addr_id');
        $records = $request->except('country', 'state', 'phonecountry', 'addr_id');

        $records['addr_user_id'] = $this->user->user_id;
        $records['addr_phone_country_id'] = Country::getCountryIdByCountryCode($request->input('addr_phone_country_id'));
        $records['addr_billing_default'] = ($records['addr_billing_default'] ? config('constants.YES') : config('constants.NO'));
        $records['addr_shipping_default'] = ($records['addr_shipping_default'] ? config('constants.YES') : config('constants.NO'));
        if (!empty($lastId)) {
            UserAddress::where('addr_id', $lastId)->update($records);
        } else {
            $lastId = UserAddress::create($records)->addr_id;
        }
        $updateValues = [];
        if ($records['addr_shipping_default'] == config('constants.YES') && $records['addr_billing_default'] == config('constants.YES')) {
            $updateValues = [
                'addr_shipping_default' => config('constants.NO'),
                'addr_billing_default' => config('constants.NO')
            ];
        } elseif ($records['addr_shipping_default'] == config('constants.YES')) {
            $updateValues = [
                'addr_shipping_default' => config('constants.NO'),
            ];
        } elseif ($records['addr_billing_default'] == config('constants.YES')) {
            $updateValues = [
                'addr_billing_default' => config('constants.NO'),
            ];
        }
        if (count($updateValues) != 0) {
            UserAddress::where('addr_user_id', $this->user->user_id)->where('addr_id', '!=', $lastId)->update($updateValues);
        }
        return Redirect::route('userAddress');
    }

    public function deleteAddress(Request $request)
    {
        UserAddress::where(['addr_user_id' => $this->user->user_id, 'addr_id' => $request->input('addr_id')])->delete();
        return Redirect::route('userAddress');
    }

    public function updateAddress(UserAddressRequest $request)
    {
        if (!empty($request->input('addr_id'))) {
            try {
                DB::beginTransaction();
                $data = $request->except(['user_phone_mask']);
                if (empty($data['addr_billing_default'])) {
                    $data['addr_billing_default'] = config('constants.NO');
                }
                if (empty($data['addr_shipping_default'])) {
                    $data['addr_shipping_default'] = config('constants.NO');
                }
                if (empty($data['user_phone_country_code'])) {
                    $data['addr_phone_country_id'] = Country::getCountryIdByCountryCode('us');
                } else {
                    $data['addr_phone_country_id'] = Country::getCountryIdByCountryCode($request->input('user_phone_country_code'));
                }
                unset($data['user_phone_country_code']);
                $userAddress = UserAddress::where('addr_id', $request->input('addr_id'))->update($data);
                if (!empty($request->input('addr_billing_default'))) {
                    UserAddress::where('addr_user_id', $this->user->user_id)->where('addr_id', '!=', $request->input('addr_id'))->update(['addr_billing_default' => config('constants.NO')]);
                } else {
                }
                if (!empty($request->input('addr_shipping_default'))) {
                    UserAddress::where('addr_user_id', $this->user->user_id)->where('addr_id', '!=', $request->input('addr_id'))->update(['addr_shipping_default' => config('constants.NO')]);
                }
                DB::commit();
                return jsonresponse(true, __('NOTI_ADDRESS_UPDATED'), $this->addressList());
            } catch (Exception $e) {
                DB::rollback();
                return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
            }
        }
    }

    public function updateAddressDefaultStatus(Request $request)
    {
        UserAddress::where('addr_user_id', $this->user->user_id)->where('addr_id', $request->input('addr_id'))->update([$request->input('type') => config('constants.YES')]);
        UserAddress::where('addr_user_id', $this->user->user_id)->where('addr_id', '!=', $request->input('addr_id'))->update([$request->input('type') => config('constants.NO')]);
        return jsonresponse(true, null);
    }

    private function addressList()
    {
        $address = UserAddress::getRecordByUserId($this->user->user_id);
        $states = State::getStates();
        $addressList = view('themes.' . config('theme') . '.dashboard.address.listAddress', compact('address', 'states'))->render();
        return $addressList;
    }

    public function getAddressList(Request $request)
    {
        return jsonresponse(true, '', $this->addressList());
    }
}
