<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use App\Models\User;
use App\Models\UserAuthToken;
use App\Helpers\FileHelper;
use App\Models\Country;
use App\Models\SmsTemplate;
use App\Models\Notification;
use App\Models\AttachedFile;
use App\Models\NotificationTemplate;
use App\Models\UsersRequestHistory;
use App\Models\UserDeviceToken;
use App\Models\AppPushNotificaton;
use App\Events\SystemNotification;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;
use File;
use PDF;
use Str;
use Carbon\Carbon;
use FacebookAds\Object\UserDevice;

class UserController extends YokartController
{
    public function deleteAccount(Request $request)
    {
        if (UsersRequestHistory::removeUserData(Auth::guard('api')->user()->user_id)) {
            $request['type'] = 'deletionrequest';
            $request['ureq_user_id'] = Auth::guard('api')->user()->user_id;
            $request['ureq_purpose'] = "";
            UsersRequestHistory::saveUserGdprRequest($request->all(), Auth::guard('api')->user()->user_id);
            /** trigger event for system notification */
            $data = NotificationTemplate::getBySlug('gdpr_deletion_request');
            $message = str_replace('{userName}', Str::title(Auth::guard('api')->user()->user_fname . ' ' . Auth::guard('api')->user()->user_lname), $data->ntpl_body);
            event(new SystemNotification([
                'type' => Notification::GDPR_DELETE_REQUEST,
                'from_id' => Auth::guard('api')->user()->user_id,
                'message' => $message
            ]));

            $notificationData = [];
            $sendSms = false;
            /*send email code*/
            $data = EmailHelper::getEmailData('gdpr_delete_account');
            $priority = $data['body']->etpl_priority;
            $data['subject'] = $this->replaceGdprTags($data['body']->etpl_subject, Auth::guard('api')->user());
            $data['body'] = $this->replaceGdprTags($data['body']->etpl_body, Auth::guard('api')->user());
            $data['to'] = Auth::guard('api')->user()->user_email;
            $notificationData['email'] = $data;

            $country = Country::select('country_phonecode')->where('country_id', Auth::guard('api')->user()->user_phone_country_id)->first();
            if (!empty($country->country_phonecode) && !empty(Auth::guard('api')->user()->user_phone)) {
                $data = SmsTemplate::getSMSTemplate('gdpr_delete_account');
                $priority = $data['body']->stpl_priority;
                $data['body'] = str_replace('{name}', Auth::guard('api')->user()->user_fname . ' ' . Auth::guard('api')->user()->user_lname, $data['body']->stpl_body);
                $notificationData['sms'] = [
                    'message' => $data['body'],
                    'recipients' => array('+' . $country->country_phonecode . Auth::guard('api')->user()->user_phone)
                ];
                $sendSms = true;
            }
            
            dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);

            $token = $request->user()->token();
            $token->revoke();
            return apiresponse(config('constants.SUCCESS'), appLang('NOTI_GDPR_DELETED'));
        }
        return apiresponse(config('constants.ERROR'), appLang('NOTI_GDPR_DELETE_ERROR'));
    }

    public function requestGdprData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ureq_purpose' => 'required'
        ]);
        $validator->setAttributeNames([
            'ureq_purpose' => 'Purpose'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        
        $request['type'] = 'datarequest';
        if (UsersRequestHistory::saveUserGdprRequest($request->all(), Auth::guard('api')->user()->user_id)) {
            $content = UsersRequestHistory::getUserData(Auth::guard('api')->user()->user_id);

            /** trigger event for system notification */
            $data = NotificationTemplate::getBySlug('gdpr_data_request');
            $message = str_replace('{userName}', Str::title(Auth::guard('api')->user()->user_fname . ' ' . Auth::guard('api')->user()->user_lname), $data->ntpl_body);
            event(new SystemNotification([
                'type' => Notification::GDPR_DATA_REQUEST,
                'from_id' => Auth::guard('api')->user()->user_id,
                'message' => $message
            ]));

            /*send email code*/
            $data = EmailHelper::getEmailData('gdpr_data_requested');
            $priority = $data['body']->etpl_priority;
            $data['subject'] = $this->replaceGdprTags($data['body']->etpl_subject, Auth::guard('api')->user());
            $data['body'] = $this->replaceGdprTags($data['body']->etpl_body, Auth::guard('api')->user());
            $data['to'] = Auth::guard('api')->user()->user_email;

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
            return apiresponse(config('constants.SUCCESS'), appLang('NOTI_GDPR_DATA_DOWNLOD'), [
                'download_url' => $destinationPath
            ]);
        }
        return apiresponse(config('constants.ERROR'), appLang('NOTI_SOMETHING_WENT_WRONG'));
    }

    private function replaceGdprTags($content, $user)
    {
        $content = str_replace('{userName}', $user->user_fname . ' ' . $user->user_lname, $content);
        $content = str_replace('{contactEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $content);
        $content = str_replace('{websiteName}', (getConfigValueByName('BUSINESS_NAME') ?? ''), $content);
        return $content;
    }

    public function profile(Request $request)
    {
        $user = User::getUser(
            [
                'user_id',
                'user_fname',
                'user_lname',
                DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'),
                'user_email',
                'user_register_type',
                'user_phone_country_id',
                'user_phone',
                'user_dob',
                'user_gender',
            ],
            [
                'user_id' => Auth::guard('api')->user()->user_id
            ]
        )->first();
        $response = $user;
        $response["user_phone_code"] = !empty($user->phoneCountry->country_phonecode) ? "+" . $user->phoneCountry->country_phonecode : "";
        $response["user_image"] = !empty($user->image->afile_id) ? url('/') . '/yokart/image/' . $user->image->afile_id . '?t=' . strtotime($user->image->afile_updated_at) : "";
        unset($response["user_phone_country_id"]);
        unset($response["phoneCountry"]);
        unset($response["image"]);
        return apiresponse(config('constants.SUCCESS'), '', ['info' => $response]);
    }

    public function updateProfile(Request $request)
    {
        $response  = User::updateProfileFromApp($request, Auth::guard('api')->user()->user_id);
        if ($response['status'] == false) {
            return apiresponse(config('constants.ERROR'), $response['message']);
        }
        $record =  User::where('user_id', Auth::guard('api')->user()->user_id)->first()->toArray();
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_PROFILE_UPDATED"), $record);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'confirm_password' => 'required',
            'new_password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'
        ], [
            'new_password.regex' => appLang("MSG_PASSWORD_VALIDATION"),
        ]);
        $validator->setAttributeNames([
            'old_password' => 'old password',
            'confirm_password' => 'confirm password',
            'new_password' => 'new password'
        ]);
        
        $validator->after(function ($validator) use ($request) {
            if (!Hash::check($request->input('old_password'), Auth::guard('api')->user()->user_password)) {
                $validator->errors()->add('old_password', appLang("NOTI_CURRENT_PASSWORD_INCORRECT"));
            }
            if ($request->input('new_password') != $request->input('confirm_password')) {
                $validator->errors()->add('confirm_password', appLang("NOTI_CONFIRM_PASSWORD_SHOULD_MATCH_NEWPASSWORD"));
            }
        });
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        User::where('user_id', Auth::guard('api')->user()->user_id)->update([
            'user_password' => Hash::make($request->input('new_password'))
        ]);
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_PASSWORD_UPDATED"));
    }

    public function sendEmailVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_email' => 'required|email',
        ]);
        $validator->setAttributeNames([
            'new_email' => 'new email'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $user = User::whereRaw("lower(`user_email`) LIKE ?", [$request->input('new_email')])->get()->count();
        if ($user >= 1) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_EMAIL_ALREADY_EXISTS"));
        }
        UserAuthToken::where('usrauth_expiry', '<', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())->delete();
        $user = User::where('user_id', Auth::guard('api')->user()->user_id)->first();
        $this->sendCodeOnEmail($user);
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CODE_SENT_SUCCESSFULLY"));
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

    public function verifyUpdatedEmail(Request $request, $type)
    {
        $passRequired = 'required';
        $otpRequired = '';
        if ($type == 'otp') {
            $passRequired = '';
            $otpRequired = 'required';
        }
        if ($request->is_p_required != 1) {
            $passRequired = '';
        }
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,user_email',
            'code' => $otpRequired,
            'password' => $passRequired
        ]);
        if ($passRequired) {
            $validator->after(function ($validator) use ($request) {
                if (!Hash::check($request->input('password'), Auth::guard('api')->user()->user_password)) {
                    $validator->errors()->add('password', appLang("NOTI_CURRENT_PASSWORD_INCORRECT"));
                }
            });
        }
       
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $userAuthToken = UserAuthToken::where('usrauth_user_id', Auth::guard('api')->user()->user_id)
            ->where('usrauth_token', $request['code'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
        if (!empty($userAuthToken)) {
            if ($type == 'otp') {
                return apiresponse(config('constants.SUCCESS'));
            }
            UserAuthToken::where('usrauth_user_id', Auth::guard('api')->user()->user_id)->delete();
            User::where('user_id', Auth::guard('api')->user()->user_id)->update([
                'user_email' => $request->input('email'),
                'user_email_verified' => 1
            ]);
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_EMAIL_UPDATED"));
        }
        return apiresponse(config('constants.ERROR'), appLang("NOTI_VERIFICATION_CODE_INCORRECT"));
    }

    public function sendPhoneVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_country_code' => 'required',
            'phone' => 'required',
        ]);
        $validator->setAttributeNames([
            'phone_country_code' => 'country code'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $country = Country::getCountries(['country_id', 'country_phonecode'], ['country_code' => $request->input('phone_country_code')])->first();
        $phoneExists = User::where("user_phone", $request->input('phone'))->where('user_phone_country_id', $country->country_id)->get()->count();
        if ($phoneExists >= 1 && $request->input('p_step') != 0) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_PHONE_ALREADY_EXISTS"));
        }
        UserAuthToken::where('usrauth_expiry', '<', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())->delete();
        $user = User::where('user_id', Auth::guard('api')->user()->user_id)->first();
        $this->sendCodeOnPhone($user, $country->country_phonecode, $request->input('phone'));
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_CODE_SENT_SUCCESSFULLY"));
    }

    private function sendCodeOnPhone($user, $countryCode, $phone)
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
            'recipients' => array('+' . $countryCode . $phone)
        ];
        dispatch(new SendNotification($notificationData, false, false, true))->onQueue($priority);
    }

    public function verifyUpdatedPhone(Request $request)
    {
       
        $unique = '|unique:users,user_phone';
        if ($request->input('p_step') == 0) {
            $unique = "";
        }
        
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'phone_country_code' => 'required',
            'phone' => 'required|numeric' . $unique,
        ]);
        //print_r($unique);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $user = User::where('user_id', Auth::guard('api')->user()->user_id)->first();

        $userAuthToken = UserAuthToken::where('usrauth_user_id', $user->user_id)
            ->where('usrauth_token', $request['code'])
            ->where('usrauth_expiry', '>=', Carbon::now()->subMinutes(config('app.expiredVerificationCode'))->toDateTimeString())
            ->first();
        if (!empty($userAuthToken)) {
            if ($request->input('p_step') == 0) {
                return apiresponse(config('constants.SUCCESS'), __('NOTI_PHONE_UPDATED'));
            }
            UserAuthToken::where('usrauth_user_id', $user->user_id)->delete();
            $country = Country::getCountries(['country_id'], ['country_code' => strtoupper($request['phone_country_code'])])->first();
            if (!empty($country->country_id)) {
                $request['user_phone_country_id'] = $country->country_id;
            }
            User::where('user_id', $user->user_id)->update([
                'user_phone_country_id' => $request['user_phone_country_id'],
                'user_phone' => $request['phone'],
                'user_phone_verified' => config('constants.YES')
            ]);
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_PHONE_UPDATED"));
        }
        return apiresponse(config('constants.ERROR'), appLang("NOTI_VERIFICATION_CODE_INCORRECT"));
    }

    public function updatePhoneDirectly(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_country_code' => 'required',
            'phone' => 'required|numeric|unique:users,user_phone',
            'password' => 'required'
        ]);
        $validator->after(function ($validator) use ($request) {
            if (!Hash::check($request->input('password'), Auth::guard('api')->user()->user_password)) {
                $validator->errors()->add('password', appLang("NOTI_CURRENT_PASSWORD_INCORRECT"));
            }
        });
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $user = User::where('user_id', Auth::guard('api')->user()->user_id)->first();

        $country = Country::getCountries(['country_id'], ['country_code' => strtoupper($request['phone_country_code'])])->first();
        if (!empty($country->country_id)) {
            $request['user_phone_country_id'] = $country->country_id;
        }

        User::where('user_id', $user->user_id)->update([
            'user_phone_country_id' => $request['user_phone_country_id'],
            'user_phone' => $request['phone'],
            'user_phone_verified' => config('constants.YES')
        ]);
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_PHONE_UPDATED"));
    }
    public function deleteImage(Request $request) 
    {
        $user = User::getUser([
            'user_id',
            'user_fname',
            'user_lname',
            DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'),
            'user_email',
            'user_phone_country_id',
            'user_phone',
            'user_dob',
            'user_gender',
        ], ['user_id' => Auth::guard('api')->user()->user_id])->first();
        $response = $user;
        $response["user_phone_code"] = !empty($user->phoneCountry->country_phonecode) ? "+" . $user->phoneCountry->country_phonecode : "";
        $response["user_image"] = '';
        if ($user->image) {
            AttachedFile::deleteAttachedFileById($user->image->afile_id);
        }
        unset($response["user_phone_country_id"]);
        unset($response["phoneCountry"]);
        unset($response["image"]);
        return apiresponse(config('constants.SUCCESS'), '', ['info' => $response]);
    }
    public function imageUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $userImage = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $akey = 0;
            $uploadedFileName = FileHelper::uploadFile($file);
            $attachedFile = AttachedFile::saveOrUpdateFile(AttachedFile::getConstantVal('userProfileImage'), Auth::guard('api')->user()->user_id, $uploadedFileName, $file->getClientOriginalName());
            $userImage = url('/') . '/yokart/image/' . $attachedFile->afile_id . '?t=' . time();
        }
        return apiresponse(config('constants.SUCCESS'), '', ['image' => $userImage]);
    }
    /* Update user device token */
    public function updateDeviceToken(Request $request)
    {
        // $data['type'] = 'Test Vivek';
        // $data['title'] = 'Test Title Vivek';
        // $data['body'] = 'Test body Vivek';
        // $deviceToken = 'fpZnFWInRzCJc_4Ee0cOQ8:APA91bEv66XbQmZxsOab4oy89zEwZdLlJ_Q7QxF5_B1Zc5Os6BrMlac8Ieg-WknG3YjIY3XyTsTcDP9ny_7GevHgl3-jPyk0iywwowjdasgv-5uI5gzGMuWh0sJmQ10ju-TZifsa6Pma';
        // sendPushNotificationRequest(UserDeviceToken::USER_DEVICE_TYPE_ANDROID, $deviceToken, $data);

        // echo 'sent';die;
        
        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
            'device_type' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $userId = Auth::guard('api')->user()->user_id;
        $sessId = request()->header('sess-token') ?? '';
        if (empty($sessId)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_INVALID_DEVICE_ID"));
        }
        $deviceType = $request->device_type;
        $deviceToken = $request->device_token;
        if (!UserDeviceToken::isTokenAlreadyExists($sessId, $deviceType, $deviceToken)) {
            UserDeviceToken::create([
                'udt_user_id' => $userId,
                'udt_sess_id' => $sessId,
                'udt_device_type' => $deviceType,
                'udt_device_token' => $deviceToken
            ])->udt_id;
        } else {
            UserDeviceToken::where([
                'udt_sess_id' => $sessId,
                'udt_device_type' => $deviceType,
                'udt_device_token' => $deviceToken
                ])->update(['udt_user_id' => $userId]);
        }
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_DEVICE_TOKEN_UPDATED"), []);
    }
    public function notifications(Request $request, $pageId)
    {
        $records = AppPushNotificaton::getRecords(Auth::guard('api')->user()->user_id, $pageId);
        $response = [];
        $total = 0;
        if (count($records) > 0) {
            $response = $records['data'];
            $total = $records['total'];
        }
        return apiresponse(config('constants.SUCCESS'), '', ['notifications' => $response, 'total' => $total]);
    }
    public function deleteNotifications(Request $request, $id = 0)
    {
        $obj = AppPushNotificaton::where('apn_user_id', Auth::guard('api')->user()->user_id);
        if ($id != 0) {
            $obj->where('apn_id', $id);
        }
        $obj->delete();
        return apiresponse(config('constants.SUCCESS'));

    }
}
