<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminEmailVerification;
use App\Models\Language;
use App\Models\Configuration;
use App\Models\Admin\AdminRole;
use App\Models\Notification;
use App\Models\AttachedFile;
use App\Models\Resource;
use App\Models\Country;
use App\Models\InstagramFeedToken;
use App\Http\Requests\AdminRequest;
use Auth;
use Illuminate\Support\Arr;
use App\Helpers\EmailHelper;
use App\Helpers\FileHelper;
use App\Jobs\SendNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Config;

class AdminController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            $this->readPermission = canRead(Auth::guard('admin')->user()->admin_role_id, AdminRole::ADMIN_USERS);
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $data = [];

        $data['admins'] = Admin::getAdminUsers($request->all(), $this->admin->admin_id);

        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['admins']->count() == 0) {
            $data['showEmpty'] = 1;
        }

        return jsonresponse(true, null, $data);
    }

    public function getRoles()
    {
        return jsonresponse(true, null, ['roles' => AdminRole::getRolesList(), 'countries' => Country::getAll(), 'defaultCountryCode'=> getDefaultCountryCode()]);
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function store(AdminRequest $request)
    {
        if (!canWrite(AdminRole::ADMIN_USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $country = Country::select('country_id')->where('country_code', strtoupper($request->input('country_code')))->first();
        $requestData = $request->except(['admin_confirm_password','country_code']);
        if (isset($country->country_id) && !empty($country->country_id)) {
            $requestData['admin_phone_country_id'] = $country->country_id;
        }
        $requestData['admin_password'] = Hash::make($requestData['admin_password']);
        $requestData['admin_publish'] = config('constants.YES');
        $requestData['admin_created_by_id'] = $requestData['admin_updated_by_id']  =  $this->admin->admin_id;
        $requestData['admin_created_at'] = $requestData['admin_updated_at']  =  Carbon::now();
        $status = Admin::insert($requestData);
        $this->sendAdminEmailNotification($requestData);
        return jsonresponse($status, __('NOTI_SUBADMIN_CREATED'));
    }

    public function updateStatus(Request $request, $id)
    {
        if (!canWrite(AdminRole::ADMIN_USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $status = $request->input('status') == 'true' ? 1:0;
        Admin::where('admin_id', $id)->update(['admin_publish' => $status, 'admin_updated_by_id' => $this->admin->admin_id, 'admin_updated_at' => Carbon::now()]);
        return jsonresponse(true, __('NOTI_SUBADMIN_STATUS_UPDATED'));
    }


    public function show(Request $request, $id)
    {
        return response()->json(['data' =>  Admin::getAdminById($id)]);
    }

    public function edit(Request $request, $id)
    {
        $roles = AdminRole::getRolesList();
        $record = Admin::getAdminById($id);
        return jsonresponse(true, null, compact('record', 'roles'));
    }

    public function update(AdminRequest $request, $id)
    {
        if (!canWrite(AdminRole::ADMIN_USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $requestData = $request->except(['admin_confirm_password','country_code', '_method', 'created_at', 'updated_at']);
        if (!empty($requestData['admin_password'])) {
            $requestData['admin_password'] = Hash::make($requestData['admin_password']);
        }
        $country = Country::select('country_id')->where('country_code', strtoupper($request->input('country_code')))->first();
        if (isset($country->country_id) && !empty($country->country_id)) {
            $requestData['admin_phone_country_id'] = $country->country_id;
        }
        $requestData['admin_updated_at'] = Carbon::now();
        $requestData['admin_updated_by_id'] = $this->admin->admin_id;
        Admin::where('admin_id', $id)->update($requestData);
        return jsonresponse(true, __('NOTI_SUBADMIN_UPDATED'));
    }


    public function emailTokenVerify($token)
    {
        $tokenData = AdminEmailVerification::where('aev_token', $token)->first();
        if (!empty($tokenData)) {
            if (Carbon::parse($tokenData->aev_expiry)->addMinutes(config('app.expiredToken')) < Carbon::now()) {
                toastr()->error(__('NOTI_INVALID_REQUEST'));
                return redirect('admin#/edit-email')->with('error', __('NOTI_INVALID_REQUEST')); /*token expired */
            }
            Admin::where('admin_id', $tokenData->aev_admin_id)->update(['admin_email' => $tokenData->aev_email]);
            AdminEmailVerification::where('aev_token', $token)->delete();
            toastr()->success(__('NOTI_ADMIN_EMAIL_UPDATED'));
            return redirect('admin#/edit-profile')->with('success', __('NOTI_ADMIN_EMAIL_UPDATED'));
        }
        toastr()->error(__('NOTI_INVALID_REQUEST'));
        return redirect('admin#/edit-email/')->with('error', __('NOTI_INVALID_REQUEST')); /*token invalid */
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::ADMIN_USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Admin::where('admin_id', $id)->delete();
        return jsonresponse(true, __('NOTI_SUBADMIN_DELETED'));
    }

    public function updateEmailAddress(Request $request)
    {
        if (!canWrite(AdminRole::ADMIN_USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $this->validate($request, [
            'email' => 'required|email',
         ]);
        AdminEmailVerification::where('aev_expiry', '<', Carbon::now()->subMinutes(config('app.expiredToken'))->toDateTimeString())->delete();
        $tokenExist = AdminEmailVerification::where('aev_admin_id', $this->admin->admin_id)->first();
        $token = '';
        if (!empty($tokenExist)) {
            $token = $tokenExist->aev_token;
        } else {
            $token = Str::random(64);
            AdminEmailVerification::insert([
                'aev_admin_id' => $this->admin->admin_id,
                'aev_email' => $request->input('email'),
                'aev_token' => $token,
                'aev_expiry' => Carbon::now()
            ]);
        }
        /*send email code*/
        $data = EmailHelper::getEmailData('admin_email_verify');
        
        $priority = $data['body']->etpl_priority;
        $data['subject'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']->etpl_subject);
        $data['body'] = str_replace('{name}', $this->admin->admin_name, $data['body']->etpl_body);
        $data['body'] = str_replace('{email}', $request->input('email'), $data['body']);
        $data['body'] = str_replace('{baseurl}', url('/'), $data['body']);
        $data['body'] = str_replace('{resetlink}', url('admin/email/verify', $token), $data['body']);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $data['body'] = str_replace('{contactUsEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $data['body']);
        $data['to'] = $this->admin->admin_email;
        dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
        return jsonresponse(true, __('NOTI_VERIFICATION_LINK_SENT'));
    }

    public function updatePassword(AdminRequest $request)
    {
        if (!canWrite(AdminRole::ADMIN_USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Admin::find($this->admin->admin_id)->update(['admin_password' => Hash::make($request->input('admin_password'))]);
        return response()->json(['success' => __('NOTI_ADMIN_PASSWORD_UPDATED')]);
    }

    public function editUserData()
    {
        $admin = Admin::with('adminProfileImage')->with('adminRole')->leftJoin('countries', 'admin_phone_country_id', '=', 'countries.country_id')->where('admin_id', $this->admin->admin_id)->first();
        $lang = Language::all();
        $countries = Country::getAll();
        $timeZoneLists = timezone_identifiers_list();
        return response()->json(['data' => $admin, 'lang' => $lang, 'countries' => $countries, 'timezone' => $timeZoneLists, 'appName' => config('app.name'), 'noImage' => asset('no_image.jpg')]);
    }

    public function updateProfile(AdminRequest $request)
    {
        if ($request->input('country_code') != '') {
            $country = Country::select('country_id')->where('country_code', strtoupper($request->input('country_code')))->first();
            $request['admin_phone_country_id'] = $country->country_id;
        }
        $user = Admin::find($request->input('admin_id'))->update($request->only(['admin_name', 'admin_default_lang', 'admin_default_timezone', 'admin_username', 'admin_phone', 'admin_phone_country_id']));
        return response()->json(['success' => __('NOTI_ADMIN_PROFILE_UPDATED')]);
    }

    public function updateProfileImage(Request $request, $id)
    {
        imageUpload($request, $id, 'profileImage');
        return response()->json(['success' => __('NOTI_USER_IMAGE_UPDATED')]);
    }

    public function getLogoDetails(Request $request)
    {
        $data = [];
        if (!empty($request['keys'])) {
            $request['keys'] = explode(',', $request['keys']);
            $data  = Configuration::getKeyValues($request['keys']);
        }
        $data['adminLogo'] =  AttachedFile::getAttachedFile(AttachedFile::getConstantVal('adminLogo'), 0);
        $data['adminDarkModeLogo'] =  AttachedFile::getAttachedFile(AttachedFile::getConstantVal('adminDarkModeLogo'), 0);
        $data['frontendDarkModeLogo'] =  AttachedFile::getAttachedFile(AttachedFile::getConstantVal('frontendDarkModeLogo'), 0);
      
        $data['frontendLogo'] =  AttachedFile::getAttachedFile(AttachedFile::getConstantVal('frontendLogo'), 0);
        $data['favicon'] =  AttachedFile::getAttachedFile(AttachedFile::getConstantVal('favicon'), 0);
        return jsonresponse(true, null, $data);
    }

    public function updateAdminLogo(Request $request)
    {
        imageUpload($request, 0, $request->input('logoType'));
        Configuration::saveValue('ADMIN_LOGO_RATIO', $request->input('imageRatio'));
        $data['adminLogo'] =  AttachedFile::getAttachedFile(AttachedFile::getConstantVal('adminLogo'), 0);
        $data['adminDarkModeLogo'] =  AttachedFile::getAttachedFile(AttachedFile::getConstantVal('adminDarkModeLogo'), 0);
        return jsonresponse(true, __('NOTI_ADMIN_LOGO_UPDATE'), $data);
    }

    public function updateFrontendLogo(Request $request)
    {
        imageUpload($request, 0, $request->input('logoType'));
        Configuration::saveValue('FRONTEND_LOGO_RATIO', $request->input('imageRatio'));
        $data['frontendLogo'] =  AttachedFile::getAttachedFile(AttachedFile::getConstantVal('frontendLogo'), 0);
        $data['frontendDarkModeLogo'] =  AttachedFile::getAttachedFile(AttachedFile::getConstantVal('frontendDarkModeLogo'), 0);
        return jsonresponse(true, __('NOTI_FRONTEND_LOGO_UPDATE'), $data);
    }

    public function updateFavicon(Request $request)
    {
        $uploadedFileName = FileHelper::uploadDigitalFiles($request->file('file'));
        $response = AttachedFile::saveOrUpdateFile(AttachedFile::getConstantVal('favicon'), 0, $uploadedFileName, $request->file('file')->getClientOriginalName());
        return jsonresponse(true, __('NOTI_FAVICON_UPDATE'), $response);
    }

    public function deleteFavicon(Request $request)
    {
        $fileName =  AttachedFile::getAttachedFile(AttachedFile::getConstantVal('favicon'), 0);
        AttachedFile::deleteFile($fileName['afile_physical_path']);
        return jsonresponse(true, __('NOTI_FAVICON_DELETED'), '');
    }

    public function removeInstaHandle(Request $request, $handle)
    {
        InstagramFeedToken::where('iftoken_username', $handle)->delete();
        return jsonresponse(true, __('NOTI_INSTA_FEED_DELETED'), '');
    }

    public function getSharethisNetworks(Request $request)
    {
        $networks = \App\Helpers\SocialHelper::getSharethisNetworks();
        $networksData = [];
        foreach ($networks as $network) {
            $networksData[$network] = Configuration::getValue('SHARETHIS_' . strtoupper($network));
        }
        return jsonresponse(true, null, ['networks' => $networksData]);
    }

    public function updateSharethisNetworks(Request $request)
    {
        $status = ($request['status'] == 'true') ? 1 : 0;
        Configuration::saveValue('SHARETHIS_' . strtoupper($request['network']), $status);
        return jsonresponse(true, __('NOTI_SHARE_NETWORK_UPDATE'), '');
    }

    public function getSystemStatus()
    {
        $statuses = Admin::moduleStats();

        return jsonresponse(true, null, ['statuses' => $statuses]);
    }
    
    public function skipGetstarted(Request $request)
    {
        Configuration::saveValue('GETSTARTED_SKIP', 1);
        return jsonresponse(true, __('NOTI_SKIP_SUCCESSFULLY'), '');
    }

    public function getAdminData()
    {
        $data = [];
        $admin = Admin::getAdminData();
        $data['admin_id'] = $admin->admin_id;
        $data['admin_name'] = $admin->admin_name;
        $data['admin_name_badge'] = substr($admin->admin_name, 0, 1);
        $data['admin_email'] = $admin->admin_email;
        $data['admin_image'] = !empty($admin->afile_id) ? url('').'/yokart/image/profileImage/'.$admin->admin_id.'/0/40-42/'.time():'';
        $data['languages'] = Language::getAll();
        $data['admin_lang'] = Config::get('app.locale');
        return jsonresponse(true, null, $data);
    }
    
    public function uploadEditorImages(Request $request)
    {
        $uploadedFileName = FileHelper::uploadDigitalFiles($request->file('file'));
        return response()->json(['location' => url('') . '/public/storage/' . $uploadedFileName]);
    }

    public function getSubAdminDashboardData(Request $request)
    {
        $data = [];
        $adminData = Admin::getAdminData(['admin_id', 'admin_name','admin_email','admin_phone_country_id', 'af.afile_id','admin_role_id'], config('constants.YES'));
        $data['admin'] = $adminData;
        $data['notifications'] = view('admin.partials.notification_item')->with('notifications', Notification::getNotifications(0))->render();
        $data['total_notification'] = Notification::getNotificationCount();
        $data['permission'] = AdminRole::getPermissions(1);
        $data['sections'] = AdminRole::SECTION_NAMES;
        $data['sectionUrls'] = AdminRole::ACTION_URL_NAME;
        return jsonresponse(true, null, $data);
    }
    public function globalSearch(Request $request)
    {
        $records = [];
        if (empty($request->input('keyword'))) {
            return jsonresponse(true, null, $records);
        }
        if (strlen($request->input('keyword')) > 3 || is_numeric($request->input('keyword'))) {
            $records['data'] = Admin::getSearchResult($request->input('keyword'));
        }
        
        if ((strlen($request->input('keyword')) <= 3 && !is_numeric($request->input('keyword'))) || (isset($records['data']) && count($records['data']) == 0)) {
            $records['module'] = $this->multiArraySearch(AdminRole::SECTIONS, $request->input('keyword'));
        }
        $records['resource'] = Resource::searchRecords($request->input('keyword'));
        return jsonresponse(true, null, $records);
    }

    public function updateAdminPassword(Request $request, $id)
    {
        if (!canWrite(AdminRole::ADMIN_USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $validator = Validator::make($request->all(), [
            'admin_password' => 'required|same:admin_confirm_password|regex:/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'admin_confirm_password' => 'required',
        ]);
        $validator->setAttributeNames([
            'admin_password' => 'password',
            'admin_confirm_password' => 'confirm passsword'
        ])->validate();
        Admin::find($id)->update(['admin_password' => Hash::make($request->input('admin_password'))]);
        return jsonresponse(true, __('NOTI_ADMIN_PASSWORD_UPDATED'), []);
    }

    private function multiArraySearch($array, $search)
    {
        $result = [];
        foreach ($array as $key => $values) {
            if (is_array($values)) {
                foreach ($values as $valkey => $value) {
                    if (strpos(Str::lower($value), $search) !== false && isset(AdminRole::ACTION_URL_NAME[$valkey])) {
                        $result[$key][AdminRole::ACTION_URL_NAME[$valkey]] = $value;
                    }
                }
            } else {
                if (strpos(Str::lower($values), $search) !== false && isset(AdminRole::ACTION_URL_NAME[$key])) {
                    $result[$key][AdminRole::ACTION_URL_NAME[$key]] = $values;
                }
            }
        }
        return $result;
    }

    private function sendAdminEmailNotification($requestData)
    {
        $data = EmailHelper::getEmailData('sub_admin_welcome');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $data['body']->etpl_subject;
        $data['body'] = str_replace('{userName}', $requestData['admin_username'], $data['body']->etpl_body);
        $data['body'] = str_replace('{email}', $requestData['admin_email'], $data['body']);
        $data['body'] = str_replace('{loginUrl}', url('/admin'), $data['body']);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $data['body'] = str_replace('{contactUsEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $data['body']);
        $data['to'] = $requestData['admin_email'];
        dispatch(new SendNotification(array('email'=> $data)))->onQueue($priority);
    }
}
