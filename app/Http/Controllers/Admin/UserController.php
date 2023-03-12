<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\User;
use App\Models\Admin\AdminRole;
use App\Http\Requests\UserRequest;
use App\Helpers\EmailHelper;
use App\Models\UserGroup;
use App\Models\Notification;
use App\Models\UserGroupMember;
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\UserRewardPoint;
use App\Models\DiscountCoupon;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\UserAddress;
use App\Jobs\SendNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Exports\UsersExport;
use Carbon\Carbon;
use Excel;
use DB;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class UserController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::USERS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $records = User::getUsers($request->all());
        $defaultCountry = getDefaultCountryCode();
        $status = ($records->count() > 0) ? true : false;

        $data = [];
        $data['records'] = $records;
        $data['emailCheck'] = Configuration::getValue('VERIFICATION_EMAIL_STATUS');

        $data['showEmpty'] = 0;
        $data['groups'] = UserGroup::getUserGroups();
        $data['defaultCountry'] = $defaultCountry;
        if (empty($request['search']) && $records->count() == 0) {
            $data['showEmpty'] = 1;
        }

        return jsonresponse($status, '', $data);
    }

    public function getUserCoupons(Request $request, $user_id)
    {
        $coupons = DiscountCoupon::getUserActiveRecords($user_id, true);
        $savedTotal = 0;
        foreach ($coupons as $k => $coupon) {
            $coupons[$k]->savedTotal = $coupon->orders->sum('order_discount_amount');
            $savedTotal = $savedTotal + $coupons[$k]->savedTotal;
        }

        $records['coupons'] = $coupons;
        $records['savedTotal'] = $savedTotal;
        $status = ($records['coupons']->count() > 0) ? true : false;
        return jsonresponse($status, '', $records);
    }
    public function getUserAddress(Request $request, $userId)
    {
        $records = UserAddress::getRecordByUserId($userId, true, $request['per_page']);
        return jsonresponse(true, null, $records);
    }

    public function getUserOrders(Request $request, $id)
    {
        $request['user_id'] = $id;
        $records = Order::getOrders($request);
        $status = ($records['orders']->count() > 0) ? true : false;
        return jsonresponse($status, '', $records);
    }

    public function getUserReviews(Request $request, $id)
    {
        $request['user_id'] = $id;
        $records = ProductReview::getRecords($request);
        $status = ($records->count() > 0) ? true : false;
        return jsonresponse($status, '', $records);
    }

    public function getUserRewards(Request $request, $id)
    {
        $request['paginate'] = config('app.pagination');
        if (!empty($request['per_page'])) {
            $request['paginate'] = $request['per_page'];
        }
        $records['rewards'] = UserRewardPoint::getRewardPointsListing($id, $request);
        $records['usablePoints'] = UserRewardPoint::getRecordsByUserId($id, true);
        $records['bussinessName'] = Configuration::getValue('BUSINESS_NAME');
        $status = ($records['rewards']->count() > 0) ? true : false;
        return jsonresponse($status, '', $records);
    }

    public function updateUserRewards(Request $request, $id)
    {
        $rewardPoints = UserRewardPoint::updateRewardPoints($id, $request['urp_type'], $request['urp_points'], $request['urp_comments'], (int) $request['point_validity']);
        if (!empty($rewardPoints['status'])) {
            return jsonresponse(false, $rewardPoints['message']);
        }
        User::where('user_id', $id)->update(['user_updated_by_id' => $this->admin->admin_id, 'user_updated_at' => Carbon::now()]);
        return jsonresponse(true, 'NOTI_REWARD_POINTS_UPDATED');
    }

    public function updateStatus(Request $request, $user_id)
    {
        if (!canWrite(AdminRole::USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = ($request->input('status') == 'true') ? 1 : 0;
        User::where('user_id', $user_id)->update(['user_publish' => $publishStatus, 'user_updated_by_id' => $this->admin->admin_id, 'user_updated_at' => Carbon::now()]);
        $message = ($request->input('status') == 'true') ? __('NOTI_USERS_PUBLISHED') : __('NOTI_USERS_UNPUBLISHED');
        return jsonresponse(true, $message);
    }

    public function show(Request $request, $id)
    {
        $usergroups = UserGroup::all();
        $user_group_labels = [];
        if (!empty($usergroups)) {
            foreach ($usergroups as $k => $v) {
                $usergroups[$k]->checked = UserGroupMember::where('ugm_ugroup_id', $v->ugroup_id)->where('ugm_user_id', $id)->count();
                if ($usergroups[$k]->checked > 0) {
                    $user_group_labels[] = $v->ugroup_name;
                }
            }
        }
        return jsonresponse(true, '', ['user' => User::getUserById($id), 'user_groups' => $usergroups, 'user_group_labels' => $user_group_labels, 'gender' => User::GENDER, 'rewardPointsEnabled' => Configuration::getValue('REWARD_POINTS_ENABLE')]);
    }



    public function update(Request $request, $id)
    {
        if (!canWrite(AdminRole::USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $countryPhoneCode = 0;
        if (!empty($request->input('user_password'))) {
            $requestData['user_password'] = Hash::make($request->input('user_password'));
        } else {
            $countryId = 0;
            if (!empty($request->input('country_code'))) {
                $country = Country::select('country_id', 'country_phonecode')->where('country_code', strtoupper($request->input('country_code')))->first();
                $countryPhoneCode = $country->country_phonecode;
                $countryId = $country->country_id;
            }
            $this->userValidate($request->all(), $id, $countryId)->validate();
            $requestData = $request->only(['user_fname', 'user_lname', 'user_email', 'user_dob', 'user_gender', 'user_phone']);
            $requestData['user_fname'] = ($requestData['user_fname'] ?? '');
            $requestData['user_lname'] = ($requestData['user_lname'] ?? '');
            $requestData['user_email'] = ($requestData['user_email'] ?? '');
            $requestData['user_dob'] = ($requestData['user_dob'] ?? '');
            $requestData['user_gender'] = ($requestData['user_gender'] ?? '');
            $requestData['user_phone'] = ($requestData['user_phone'] ?? '');
            $requestData['user_phone_country_id'] = $country->country_id;
        }
        $requestData['user_updated_at'] = Carbon::now();
        $requestData['user_updated_by_id'] = $this->admin->admin_id;
        User::where('user_id', $id)->update($requestData);
        if (isset($request['groups'])) {
            $groups = [];
            if (!empty($request->input('groups'))) {
                $groups = explode(',', $request->input('groups'));
            }
            UserGroupMember::where('ugm_user_id', $id)->whereNotIn('ugm_ugroup_id', $groups)->delete();
            foreach ($groups as $group) {
                $count = UserGroupMember::where('ugm_user_id', $id)->where('ugm_ugroup_id', $group)->count();
                if ($count == 0) {
                    UserGroupMember::insert([
                        'ugm_user_id' => $id,
                        'ugm_ugroup_id' => $group
                    ]);
                }
            }
        }
        return jsonresponse(true, __('NOTI_USERS_UPDATED'), ['phoneCode' => $countryPhoneCode]);
    }

    private function userValidate($data, $id, $countryId = 0)
    {
        $rules = [
            'user_fname' => ['required', 'string', 'max:150'],
            'user_lname' => ['required', 'string', 'max:150']
        ];

        if (array_key_exists('user_email', $data) && !empty($data['user_email'])) {
            $tempRules = ['required', 'string', 'email', 'max:255'];
            $tempRules[] = 'unique:users,user_email,' . $id . ',user_id';
            $rules['user_email'] = $tempRules;
        }
        if (array_key_exists('user_phone', $data) && !empty($data['user_phone'])) {
            $tempRules = ['required'];
            $tempRules[] = Rule::unique('users')->where('user_phone_country_id', $countryId)->ignore($id, 'user_id');
            $rules['user_phone'] = $tempRules;
        }

        $validator = Validator::make($data, $rules);

        return $validator->setAttributeNames([
            'user_fname' => 'first name',
            'user_lname' => 'last name',
            'user_email' => 'email',
            'user_phone' => 'phone number'
        ]);
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        User::where('user_id', $id)->delete();
        UserGroupMember::where('ugm_user_id', $id)->delete();
        return jsonresponse(true, __('NOTI_USERS_DELETED'));
    }

    public function sendMail(Request $request, $user_id)
    {
        if (!canWrite(AdminRole::USERS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $user = User::select('user_fname', 'user_lname', 'user_email')->where('user_id', $user_id)->first();

        $message = $request->input('message');
        $subject = $request->input('subject');

        $data = EmailHelper::getEmailData('email_from_admin_to_buyer');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $this->replaceBuyerMailTags($data['body']->etpl_subject, $user, $subject, $message);
        $data['body'] = $this->replaceBuyerMailTags($data['body']->etpl_body, $user, $subject, $message);
        $data['to'] = $user->user_email;
        dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
        
        return jsonresponse(true, __('NOTI_EMAILS_SENT'));
    }

    private function replaceBuyerMailTags($content, $user, $subject, $message)
    {
        $content = str_replace('{subject}', $subject, $content);
        $content = str_replace('{name}', $user->user_fname . ' ' . $user->user_lname, $content);
        $content = str_replace('{messageContent}', $message, $content);
        $content = str_replace('{contactEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $content);
        $content = str_replace('{websiteName}', (getConfigValueByName('BUSINESS_NAME') ?? ''), $content);
        return $content;
    }

    public function userSearch(Request $request)
    {
        $oldSeached = [];
        if (isset($request['searchedUsers'])) {
            $users = json_decode($request['searchedUsers'], true);
            if (count($users) > 0) {
                $oldSeached =  Arr::pluck($users, 'id');
            }
        }
        $records = User::searchUser($request->get('query'), $oldSeached);
        return jsonresponse(true, null, $records);
    }

    public function exportUsers(Request $request)
    {
        $data = new UsersExport($request->all());
        $name = 'usser-' . time() . '.xls';
        Excel::store($data, 'public/excel/' . $name);
        return jsonresponse(true, __('NOTI_USERS_EXPORTED'), url('storage/excel/' . $name));
    }
    public function loginUserById(Request $request, $id)
    {
        Auth::logout();
        Auth::loginUsingId($id);
        $request->session()->flash('loginSuccess', 1);
        return redirect('/user/profile');
    }
}
