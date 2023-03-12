<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\AttachedFile;
use App\Models\SmsTemplate;
use App\Models\Notification;
use App\Models\Country;
use App\Models\Admin\AdminRole;
use App\Models\Product;
use App\Models\OrderReturnRequest;
use App\Jobs\SendNotification;
use App\Helpers\EmailHelper;
use Request;
use Auth;
use Hash;
use DB;

class Admin extends Authenticatable
{
    use Notifiable;

    const CREATED_AT = 'admin_created_at';
    const UPDATED_AT = 'admin_updated_at';
    protected $guard = 'admin';

    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'admin_name', 'admin_username', 'admin_email', 'admin_phone', 'admin_phone_country_id', 'admin_password', 'admin_default_lang', 'admin_default_timezone', 'admin_role_id', 'admin_publish'
    ];

    protected $hidden = [
        'admin_password'
    ];

    public function scopeRoleNotNull($query)
    {
        return $query->where('admin_role_id', '<>', null);
    }

    public function adminRole()
    {
        return $this->belongsTo('App\Models\Admin\AdminRole', 'admin_role_id', 'role_id');
    }

    public static function prepareDataForNotification($data)
    {
        if (in_array($data['type'], [Notification::PRODUCT_REVIEW_POSTED, Notification::PRODUCT_REVIEW_EDITED])) {
            $roleModule = AdminRole::PRODUCT_REVIEWS;
        } elseif (in_array($data['type'], [Notification::ORDER_CREATED_BY_USER, Notification::ORDER_CREATED_BY_ADMIN, Notification::ORDER_CANCELLATION, Notification::ORDER_PARTIAL_CANCELLATION, Notification::ORDER_RETURN, Notification::ORDER_PARTIAL_RETURN, Notification::ORDER_PAYMENT, Notification::ORDER_COMMENT, Notification::ORDER_CANCELLATION_COMMENT, Notification::ORDER_RETURN_COMMENT])) {
            $roleModule = AdminRole::ORDERS;
        } elseif (in_array($data['type'], [Notification::BLOG_COMMENT])) {
            $roleModule = AdminRole::BLOGS;
        } elseif (in_array($data['type'], [Notification::USER_REGISTER, Notification::NEWSLETTER_SUBSCRIBED])) {
            $roleModule = AdminRole::USERS;
        } elseif (in_array($data['type'], [Notification::GDPR_DATA_REQUEST, Notification::GDPR_DELETE_REQUEST])) {
            $roleModule = AdminRole::GDPR_REQUEST;
        } elseif (in_array($data['type'], [Notification::TODO_REMINDER])) {
            return $data['from_id'];
        }
        $superAdmin = Admin::where('admin_role_id', null)->pluck('admin_id')->toArray();
        $subAdmin = Admin::where('admin_publish', config('constants.YES'));
        if ($roleModule != 0) {
            $subAdmin->join('admin_permissions', 'admin_permissions.ap_role_id', 'admins.admin_role_id')
            ->where('admin_permissions.ap_section_id', $roleModule)
            ->where('admin_permissions.ap_value', '<>', config('constants.NO'));
        }
        $subAdmins = $subAdmin->pluck('admin_id')->toArray();
        return array_merge($superAdmin, $subAdmins);
    }

    public static function getAdminsByModule($module)
    {
        $superAdmin = Admin::select('admin_id', 'admin_email', 'admin_name', 'admin_phone_country_id', 'admin_phone')->where('admin_role_id', null)->get()->toArray();
        $subAdmin = Admin::select('admin_id', 'admin_email', 'admin_name', 'admin_phone_country_id', 'admin_phone')->where('admin_publish', config('constants.YES'));
        if ($module != 0) {
            $subAdmin->join('admin_permissions', 'admin_permissions.ap_role_id', 'admins.admin_role_id')
            ->where('admin_permissions.ap_section_id', $module)
            ->where('admin_permissions.ap_value', '<>', config('constants.NO'));
        }
        $subAdmins = $subAdmin->get()->toArray();
        return array_merge($superAdmin, $subAdmins);
    }

    public static function getAdminUsers($request, $adminId = 0)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }

        $query = Admin::select('admin_id', 'admin_name', 'admin_username', 'admin_email', 'admin_role_id', 'admin_publish')
        ->where('admin_id', '!=', $adminId)->whereNotNull('admin_role_id')
            ->with('adminRole');
        if (!empty($request['search'])) {
            $query->where(function ($whereQuery) use ($request) {
                $whereQuery->where('admin_name', 'like', '%'.$request['search'].'%')->orWhere('admin_username', 'like', '%'.$request['search'].'%')->orWhere('admin_email', 'like', '%'.$request['search'].'%');
            });
        }
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->orderByRaw("FIELD(admin_id , '1') DESC")->latest('admin_id')->paginate((int)$per_page);
        //$query->orderByRaw("FIELD(admin_id , '1') ASC")->oldest('page_id')
        } else {
            //return $query->latest('admin_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
            return $query->orderByRaw("FIELD(admin_id , '1') DESC")->latest('admin_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }
    public static function getAdminById($id)
    {
        return Admin::select('admin_id', 'admin_name', 'admin_username', 'admin_email', 'admin_phone_country_id', 'admin_phone', 'admin_role_id', 'admin_publish', 'country_code', 'admin_created_by_id', 'admin_updated_by_id', 'admin_created_at', 'admin_updated_at')
            ->with(['createdAt', 'updatedAt'])
            ->where('admin_id', $id)
            ->leftJoin('countries', 'admin_phone_country_id', '=', 'countries.country_id')
            ->first();
    }
    public function adminProfileImage()
    {
        return $this->belongsTo('App\Models\AttachedFile', 'admin_id', 'afile_record_id');
    }
    public function getAuthPassword()
    {
        return $this->admin_password;
    }
    public function getRememberToken()
    {
        return $this->admin_remember_token;
    }
    public function setRememberToken($value)
    {
        $this->admin_remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'admin_remember_token';
    }
    
    public static function getSuperAdminId()
    {
        return Admin::select('admin_id', 'admin_email', 'admin_name')->where('admin_role_id', '=', null)->first();
    }

    public function messageFrom()
    {
        return $this->morphOne('App\Model\ThreadMessage', 'messageFrom');
    }

    public static function moduleStats()
    {
        $statuses = [];

        $logo = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('frontendLogo'), 0);
        $statuses['storeinfo'] = !empty($logo->afile_physical_path)?1:0;

        $paymentGatewayCount = \App\Models\Package::getActivePaymentGateways('paymentGateways')->count();
        $statuses['paymentgateway'] = ($paymentGatewayCount > 0)?1:0;

        $statuses['contentpages'] = (\App\Models\Configuration::getValue('GETSTARTED_CONTENTPAGES') == 1)?1:0;

        $shippingCount = \App\Models\ShippingProfile::getCount()->count();
        $statuses['shipping'] = ($shippingCount > 0)?1:0;

        $taxCount = \App\Models\TaxGroup::getCount();
        $statuses['tax'] = ($taxCount > 0)?1:0;

        $brandCount = \App\Models\Brand::getCount();
        $statuses['brand'] = ($brandCount > 0)?1:0;

        $categoryCount = \App\Models\ProductCategory::getCount();
        $statuses['category'] = ($categoryCount > 0)?1:0;

        $productCount = \App\Models\Product::getCount();
        $statuses['product'] = ($productCount > 0)?1:0;

        $statuses['homepage'] = (\App\Models\Configuration::getValue('GETSTARTED_HOMEPAGE') == 1)?1:0;

        $statuses['localization'] = 1;
                
        $statuses['sum'] = array_sum($statuses);

        $progress = ($statuses['sum'] != 0) ? $statuses['sum'] . '0' : '0';
        $statuses['progress'] = $progress;

        return $statuses;
    }

    public static function handleLoginErrors($username, $password = '')
    {
        $errorMessages = [];
        
        $emailExists = Admin::select('admin_name', 'admin_email', 'admin_phone', 'admin_phone_country_id', 'admin_password')->where('admin_email', $username)->first();
        $usernameExists = Admin::select('admin_name', 'admin_email', 'admin_phone', 'admin_phone_country_id', 'admin_password')->where('admin_username', $username)->first();
        if (empty($emailExists) && empty($usernameExists)) {
            $errorMessages['username'] = __('You have entered an incorrect username');
        } elseif ((isset($emailExists->admin_password) &&  Hash::check($password, $emailExists->admin_password) == false) || (isset($usernameExists->admin_password) && Hash::check($password, $usernameExists->admin_password) == false)) {
            $errorMessages['password'] = __('You have entered an incorrect password');
            /* send email and sms to user whose account has encurred failed attempt */
            $admin = !empty($emailExists) ? $emailExists : $usernameExists;
                
            $notificationData = [];
            $sendSms = false;
            
            $dateTime = date('Y-m-d H:i');
            $ip = Request::ip();
            $data = \Location::get($ip);
            $location = (!empty($data) && $data != false)?($data->cityName . ', ' . $data->regionName . ', ' . $data->countryName):'';

            $slug = 'failed_admin_login_attempt';
            if (!empty($admin->admin_phone) && !empty($admin->admin_phone_country_id)) {
                $country = Country::select('country_phonecode')->where('country_id', $admin->admin_phone_country_id)->first();
                /*send sms*/
                $data = SmsTemplate::getSMSTemplate($slug);
                $priority = $data['body']->stpl_priority;
                $data['body'] = str_replace('{name}', $admin->admin_name, $data['body']->stpl_body);
                $notificationData['sms'] = [
                    'message'=> $data['body'],
                    'recipients'=> array('+' . $country->country_phonecode . $admin->admin_phone)
                ];
                $sendSms = true;
            }
            
            /*send email*/
            $data = EmailHelper::getEmailData($slug);
            $priority = $data['body']->etpl_priority;
            $data['subject'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']->etpl_subject);
            $data['body'] = str_replace('{name}', $admin->admin_name, $data['body']->etpl_body);
            $data['body'] = str_replace('{location}', $location, $data['body']);
            $data['body'] = str_replace('{ip}', $ip, $data['body']);
            $data['body'] = str_replace('{datetime}', $dateTime, $data['body']);
            $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
            $data['to'] = $admin->admin_email;
            $notificationData['email'] = $data;
            dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
        } else {
            $errorMessages['username'] = __('Your account is deactivated!!');
        }

        return $errorMessages;
    }
    public static function getAdminData($fields = [], $roleJoin = 0)
    {
        if (empty($fields)) {
            $fields = ['admin_id', 'admin_name', 'af.afile_id'];
        }
        $query = Admin::select($fields)->where('admin_id', Auth::guard('admin')->user()->admin_id);
        if ($roleJoin == 1) {
            $query->with('adminRole', 'country');
        }
        return $query->leftJoin('attached_files AS af', function ($join) {
            $join->on('admin_id', '=', 'af.afile_record_id');
            $join->where('af.afile_type', AttachedFile::getConstantVal('profileImage'));
        })->first();
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'admin_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'admin_updated_by_id')->select(['admin_id', 'admin_username']);
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'admin_phone_country_id', 'country_id');
    }
    public static function getSearchResult($keyword)
    {
        $category = \App\Models\ProductCategory::select('cat_name as title', DB::raw('"categories" as type, 0 as qty, cat_id as id, "' . AdminRole::ACTION_URL_NAME['CATEGORIES'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('cat_name', 'like', '%' . $keyword . '%');
        
        $options = \App\Models\Option::select('option_name as title', DB::raw('"Option Groups" as type, 0 as qty, option_id as id, "' . AdminRole::ACTION_URL_NAME['OPTIONS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('option_name', 'like', '%' . $keyword . '%');

        $reviews = \App\Models\ProductReview::select('preview_title as title', DB::raw('"Product Reviews" as type, 0 as qty, preview_id as id, "' . AdminRole::ACTION_URL_NAME['PRODUCT_REVIEWS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('preview_title', 'like', '%' . $keyword . '%');

        $sprice = \App\Models\SpecialPrice::select('splprice_name as title', DB::raw('"Special Prices" as type, 0 as qty, splprice_id as id, "' . AdminRole::ACTION_URL_NAME['SPECIAL_PRICES'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('splprice_name', 'like', '%' . $keyword . '%');

        $dcoupon = \App\Models\DiscountCoupon::select('discountcpn_code as title', DB::raw('"Discount Coupons" as type, 0 as qty, discountcpn_id as id, "' . AdminRole::ACTION_URL_NAME['DISCOUNT_COUPONS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('discountcpn_code', 'like', '%' . $keyword . '%');

        $orders = \App\Models\Order::select('order_id as title', DB::raw('"Orders" as type, 0 as qty, order_id as id, "' . AdminRole::ACTION_URL_NAME['ORDER_DETAIL'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('order_id', 'like', '%' . $keyword . '%');

        $requestOrders = OrderReturnRequest::select('orrequest_id as title', DB::raw('(
            CASE
                WHEN orrequest_type = ' . OrderReturnRequest::RETURN . ' THEN "' . OrderReturnRequest::TYPE[OrderReturnRequest::RETURN] . '" 
                ELSE "' . OrderReturnRequest::TYPE[OrderReturnRequest::CANCELLATION] . '" END
            ) as type, 0 as qty, CONCAT(orrequest_order_id, "<>", orrequest_id) as id, "' . AdminRole::ACTION_URL_NAME['ORDER_REQUESTS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('orrequest_id', 'like', '%' . $keyword . '%');

        $pages = \App\Models\Page::select('page_name as title', DB::raw('"Pages" as type, 0 as qty, page_id as id, "' . AdminRole::ACTION_URL_NAME['PAGES'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('page_name', 'like', '%' . $keyword . '%');

        $bcategory = \App\Models\BlogPostCategory::select('bpcat_name as title', DB::raw('"Blog Categories" as type, 0 as qty, bpcat_id as id, "' . AdminRole::ACTION_URL_NAME['BLOG_CATEGORIES'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('bpcat_name', 'like', '%' . $keyword . '%');
        $blogpost = \App\Models\BlogPost::select('post_title as title', DB::raw('"Blog Articles" as type, 0 as qty, post_id as id, "' . AdminRole::ACTION_URL_NAME['BLOGS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('post_title', 'like', '%' . $keyword . '%');
        $faq = \App\Models\Faq::select('faq_title as title', DB::raw('"FAQs" as type, 0 as qty, faq_id as id, "' . AdminRole::ACTION_URL_NAME['FAQ'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('faq_title', 'like', '%' . $keyword . '%');

        $testimonial = \App\Models\Testimonial::select('testimonial_title as title', DB::raw('"Testimonials" as type, 0 as qty, testimonial_id as id, "' . AdminRole::ACTION_URL_NAME['TESTIMONIALS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('testimonial_title', 'like', '%' . $keyword . '%');
        $admin = Admin::select('admin_name as title', DB::raw('"Admin" as type, 0 as qty, admin_id as id, "' . AdminRole::ACTION_URL_NAME['ADMIN_USERS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('admin_name', 'like', '%' . $keyword . '%')->orWhere('admin_email', 'like', '%' . $keyword . '%');

        $users = \App\Models\User::select(DB::raw('CONCAT(user_fname, " ", user_lname) as title, "Users" as type, 0 as qty, user_id as id, "' . AdminRole::ACTION_URL_NAME['USERS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('user_fname', 'like', '%' . $keyword . '%')->orWhere('user_lname', 'like', '%' . $keyword . '%')->orWhere('user_email', 'like', '%' . $keyword . '%');

        

        $roles = AdminRole::select('role_name as title', DB::raw('"Admin Roles" as type, 0 as qty, role_id as id, "' . AdminRole::ACTION_URL_NAME['ROLES'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('role_name', 'like', '%' . $keyword . '%');

        $uGroups = \App\Models\UserGroup::select('ugroup_name as title', DB::raw('"Users Groups" as type, 0 as qty, ugroup_id as id, "' . AdminRole::ACTION_URL_NAME['USER_GROUPS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('ugroup_name', 'like', '%' . $keyword . '%');
        
        $tax = \App\Models\TaxGroup::select('taxgrp_name as title', DB::raw('"Tax" as type, 0 as qty, taxgrp_id as id, "' . AdminRole::ACTION_URL_NAME['TAX_SETTINGS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('taxgrp_name', 'like', '%' . $keyword . '%');

        $shipping = \App\Models\ShippingProfile::select('sprofile_name as title', DB::raw('"Shipping Profiles" as type, 0 as qty, sprofile_id as id, "' . AdminRole::ACTION_URL_NAME['SHIPPING_PROFILE_DETAIL'] . '" as url, 0 as varients_sum, 0 as varients_count'))
        ->where('sprofile_name', 'like', '%' . $keyword . '%');

        $brands = \App\Models\Brand::select('brand_name as title', DB::raw('"brands" as type, 0 as qty, brand_id as id, "' . AdminRole::ACTION_URL_NAME['BRANDS'] . '" as url, 0 as varients_sum, 0 as varients_count'))
            ->where('brand_name', 'like', '%' . $keyword . '%');
        $products = Product::select('prod_name as title', DB::raw('"products" as type, prod_stock as qty, prod_id as id, "' . AdminRole::ACTION_URL_NAME['PRODUCTS'] . '" as url'))->where('prod_name', 'like', '%' . $keyword . '%')
            ->withCount('varients')->withCount(['varients AS varients_sum' => function ($query) {
                $query->select(DB::raw('SUM(pov_quantity) as varients_sum'));
            }])
            ->union($orders)
            ->union($requestOrders)
            ->union($sprice)
            ->union($dcoupon)
            ->union($brands)
            ->union($category)
            ->union($options)
            ->union($pages)
            ->union($admin)
            ->union($users)
            ->union($roles)
            ->union($uGroups)
            ->union($reviews)
            ->union($bcategory)
            ->union($blogpost)
            ->union($faq)
            ->union($tax)
            ->union($shipping)
            ->union($testimonial)
            ->get();
        return $products;
    }

    public static function getAllAdminNames()
    {
        return Admin::select('admin_username as key', 'admin_username as value')->where('admin_id', '!=', Auth::guard('admin')->user()->admin_id)->where('admin_publish', config('constants.YES'))->get()->toArray();
    }
}
