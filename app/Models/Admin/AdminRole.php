<?php

namespace App\Models\Admin;

use App\Models\Admin\AdminYokartModel;
use App\Models\Admin\AdminPermission;
use DB;
use Auth;

class AdminRole extends AdminYokartModel
{
    const CREATED_AT = 'role_created_at';
    const UPDATED_AT = 'role_updated_at';

    const BRANDS = 1;
    const CATEGORIES = 2;
    const OPTIONS = 3;
    const PRODUCTS = 4;
    const PRODUCT_REVIEWS = 5;
    const SPECIAL_PRICES = 6;
    const DISCOUNT_COUPONS = 7;
    const BUY_TOGETHER_PRODUCTS = 8;
    const RELATED_PRODUCTS = 9;
    const REWARDS_POINTS = 10;
    const ADD_ORDERS = 11;
    const ORDERS = 12;

    const PAGES = 13;
    const BLOGS = 14;
    const FAQ = 15;
    const TESTIMONIALS = 16;
    const EMAIL_TEMPLATES = 17;
    const SMS_TEMPLATES = 18;
    const NOTIFICATION_TEMPLATES = 19;
    const LANGUAGE_LABELS = 20;

    const META_TAGS = 21;
    const URL_REWRITE = 22;
    const ALT_IMAGES = 23;
    const MISCELLANEOUS = 24;
    
    const ADMIN_USERS = 25;
    const USERS = 26;
    const GDPR_REQUEST = 27;

    const SALE_REPORT = 28;
    const PROFIT_MARGIN_REPORT = 29;
    const ACQUISITION_REPORT = 30;
    const BEHAVIOUR_REPORT = 31;
    const CUSTOMER_REPORT = 32;

    const LOCALIZATION = 33;
    const TAX_SETTINGS = 34;
    const SHIPPING_FULFILMENT = 35;
    const PAYMENT_METHODS = 36;
    const PRODUCT_SETTINGS = 37;
    const SYSTEM_SETTINGS = 38;
    const THIRD_PARTY_INTEGRATION = 39;
    const IMPORT_EXPORT = 40;
    const VIEW_DASHBOARD = 41;

    const SHIPPING_ZONE_RATE = 42;
    const PICKUP_ADDRESS = 43;
    const RETURN_AND_CANCELLATION = 44;

    const INVOICE_MANAGEMENT = 45;

    const MESSAGES = 46;

    /* Additional  Modules */
    const BLOG_CATEGORIES = 47;
    const ROLES = 48;
    const USER_GROUPS = 49;
    const ORDER_DETAIL = 50;
    const ORDER_REQUESTS = 51;
    const SHIPPING_PROFILE_DETAIL = 52;

    const ADD_PRODUCTS = 53;
    const TRACKING_API = 54;

    const SECTION_NAMES = [
        self::BRANDS =>  'BRANDS',
        self::CATEGORIES =>  'CATEGORIES',
        self::OPTIONS  =>  'OPTIONS',
        self::PRODUCTS  =>  'PRODUCTS',
        self::PRODUCT_REVIEWS =>  'PRODUCT_REVIEWS',
        
        self::SPECIAL_PRICES => 'SPECIAL_PRICES',
        self::DISCOUNT_COUPONS => 'DISCOUNT_COUPONS',
        self::BUY_TOGETHER_PRODUCTS => 'BUY_TOGETHER_PRODUCTS',
        self::RELATED_PRODUCTS => 'RELATED_PRODUCTS',
        self::REWARDS_POINTS => 'REWARDS_POINTS',

        self::ADD_ORDERS => 'ADD_ORDERS',
        self::ORDERS => 'ORDERS',

        self::PAGES => 'PAGES',
        self::BLOGS => 'BLOGS',
   
        self::FAQ => 'FAQ',
        self::TESTIMONIALS => 'TESTIMONIALS',
        self::EMAIL_TEMPLATES => 'EMAIL_TEMPLATES',
        self::SMS_TEMPLATES => 'SMS_TEMPLATES',
        self::NOTIFICATION_TEMPLATES => 'NOTIFICATION_TEMPLATES',
        self::LANGUAGE_LABELS =>  'LANGUAGE_LABELS',

        self::META_TAGS => 'META_TAGS',
        self::URL_REWRITE => 'URL_REWRITE',
        self::ALT_IMAGES => 'ALT_IMAGES',
        self::MISCELLANEOUS =>  'MISCELLANEOUS',

        self::ADMIN_USERS => 'ADMIN_USERS',
        self::USERS => 'USERS',
        self::USER_GROUPS => 'USER_GROUPS',
        self::GDPR_REQUEST => 'GDPR_REQUEST',

        self::SALE_REPORT => 'SALE_REPORT',
        self::PROFIT_MARGIN_REPORT => 'PROFIT_MARGIN_REPORT',
        self::ACQUISITION_REPORT => 'ACQUISITION_REPORT',
        self::BEHAVIOUR_REPORT => 'BEHAVIOUR_REPORT',
        self::CUSTOMER_REPORT => 'CUSTOMER_REPORT',
        
        self::LOCALIZATION => 'LOCALIZATION',
        self::TAX_SETTINGS => 'TAX_SETTINGS',
        self::SHIPPING_PROFILE_DETAIL => 'SHIPPING_PROFILE_DETAIL',
        self::SHIPPING_FULFILMENT => 'SHIPPING_FULFILMENT',
        self::PAYMENT_METHODS => 'PAYMENT_METHODS',
        self::PRODUCT_SETTINGS => 'PRODUCT_SETTINGS',
        self::SYSTEM_SETTINGS => 'SYSTEM_SETTINGS',
        self::THIRD_PARTY_INTEGRATION => 'THIRD_PARTY_INTEGRATION',
        self::IMPORT_EXPORT => 'IMPORT_EXPORT',

        self::VIEW_DASHBOARD => 'VIEW_DASHBOARD',

        self::INVOICE_MANAGEMENT => 'INVOICE_MANAGEMENT',
        self::SHIPPING_ZONE_RATE => 'SHIPPING_ZONE_RATE',
        self::PICKUP_ADDRESS => 'PICKUP_ADDRESS',
        self::RETURN_AND_CANCELLATION => 'RETURN_AND_CANCELLATION',

        self::MESSAGES => 'MESSAGES',
        self::BLOG_CATEGORIES => 'BLOG_CATEGORIES',
        self::ROLES => 'ROLES',
        self::ORDER_REQUESTS => 'ORDER_REQUESTS',
        self::ORDER_DETAIL => 'ORDER_DETAIL',

        self::ADD_PRODUCTS => 'ADD_PRODUCTS',

        self::TRACKING_API => 'TRACKING_API'
    ];

    protected $primaryKey = 'role_id';

    const SECTIONS = [
        'Products' => [
            'BRANDS' => 'LBL_BRANDS',
            'CATEGORIES' => 'LBL_CATEGORIES',
            'OPTIONS' => 'LBL_OPTION_GROUPS',
            'PRODUCTS'   => 'LBL_PRODUCTS',
            'PRODUCT_REVIEWS' => 'LBL_PRODUCT_REVIEWS',
            'ADD_PRODUCTS' => 'LBL_ADD_PRODUCTS'
        ],
        'Promotions' => [
            'SPECIAL_PRICES' => 'LBL_SPECIAL_PRICES',
            'DISCOUNT_COUPONS' => 'LBL_DISCOUNT_COUPONS',
            'REWARDS_POINTS' => 'LBL_REWARD_POINTS',
            'BUY_TOGETHER_PRODUCTS' => 'LBL_BUY_TOGETHER_PRODUCTS',
            'RELATED_PRODUCTS' => 'LBL_RELATED_PRODUCTS'
        ],
        'Orders' => [
            'ORDERS' => 'LBL_ORDERS',
            'ADD_ORDERS' => 'LBL_ADD_ORDER'
        ],
        'Cms' =>  [
            'PAGES' => 'LBL_PAGES',
            'BLOGS' => 'LBL_BLOGS',
            'FAQ' => 'LBL_FAQS',
            'TESTIMONIALS' => 'LBL_TESTIMONIALS',
            'EMAIL_TEMPLATES' => 'LBL_EMAIL_TEMPLATES',
            'SMS_TEMPLATES' => 'LBL_SMS_TEMPLATES',
            'NOTIFICATION_TEMPLATES' => 'LBL_NOTIFICATION_TEMPLATES',
            'LANGUAGE_LABELS' => 'LBL_LANGUAGE_LABELS',
        ],
        'Seo' => [
            'META_TAGS' => 'LBL_META_TAGS',
            'URL_REWRITE' => 'LBL_URL_REWRITE',
            'ALT_IMAGES' => 'LBL_ALT_IMAGES',
            'MISCELLANEOUS' => 'LBL_MISCELLANEOUS'
        ],
        'Users' =>  [
            'ADMIN_USERS' => 'LBL_ADMIN_USERS',
            'USERS' => 'LBL_USERS',
            'GDPR_REQUEST' => 'LBL_GDPR_REQUESTS'
        ],
        'Reports' =>  [
            'SALE_REPORT' => 'LBL_SALES',
            'PROFIT_MARGIN_REPORT' => 'LBL_PROFIT_MARGIN',
            'ACQUISITION_REPORT' => 'LBL_ACQUISITIONS',
            'BEHAVIOUR_REPORT' => 'LBL_BEHAVIOUR',
            'CUSTOMER_REPORT' => 'LBL_CUSTOMERS',
        ],
        'System Settings' => [
            'LOCALIZATION' => 'LBL_LOCALIZATION',
            'PAYMENT_METHODS' => 'LBL_PAYMENT_METHOD',
            'PRODUCT_SETTINGS' => 'LBL_PRODUCT_SETTINGS',
            'SYSTEM_SETTINGS' => 'LBL_SYSTEM_SETTINGS',
            'THIRD_PARTY_INTEGRATION' => 'LBL_THIRD_PARTY_INTEGRATION',
            'IMPORT_EXPORT' => 'LBL_IMPORT_EXPORT',
            'TRACKING_API' => 'LBL_TRACKING_API'
        ],
        'Dashboard' => [
            'VIEW_DASHBOARD' => 'LBL_DASHBOARD'
        ],
        'Tax Settings' => [
            'TAX_SETTINGS' => 'LBL_TAX_MANAGEMENT',
            'INVOICE_MANAGEMENT' => 'LBL_INVOICE_MANAGEMENT'
        ],
        'Shipping & Fulfilment' => [
            'SHIPPING_FULFILMENT' => 'LBL_GENERAL_SHIPPING',
            'SHIPPING_ZONE_RATE' => 'LBL_GENERAL_ZONES_RATES',
            'PICKUP_ADDRESS' => 'LBL_PICKUP_ADDRESS',
            'RETURN_AND_CANCELLATION' => 'LBL_RETURN_CANCELLATION'
        ],
        'Messages' => [
            'MESSAGES' => 'LBL_MESSAGES'
        ]
    ];

  
    const ACTION_URL_NAME = [
        self::SECTION_NAMES[self::VIEW_DASHBOARD] => 'dashboard',
        self::SECTION_NAMES[self::BRANDS] => 'brands',
        self::SECTION_NAMES[self::CATEGORIES] => 'productCategory',
        self::SECTION_NAMES[self::OPTIONS] => 'options',
        self::SECTION_NAMES[self::PRODUCTS] => 'products',
        self::SECTION_NAMES[self::PRODUCT_REVIEWS] => 'productReviewsListing',

        self::SECTION_NAMES[self::SPECIAL_PRICES] => 'specialPrices',
        self::SECTION_NAMES[self::DISCOUNT_COUPONS] => 'discountCoupons',
        self::SECTION_NAMES[self::BUY_TOGETHER_PRODUCTS] => 'buyTogetherProducts',
        self::SECTION_NAMES[self::RELATED_PRODUCTS] => 'relatedProducts',
        self::SECTION_NAMES[self::REWARDS_POINTS] => 'rewardPoints',

        self::SECTION_NAMES[self::ADD_ORDERS] => 'addOrder',
        self::SECTION_NAMES[self::ORDERS] => 'orders',
        self::SECTION_NAMES[self::ORDER_DETAIL] => 'orderDetail',
        self::SECTION_NAMES[self::ORDER_REQUESTS] => 'orderReturnDetail',

        self::SECTION_NAMES[self::PAGES] => 'pages',
        self::SECTION_NAMES[self::BLOGS] => 'blogs',
        self::SECTION_NAMES[self::BLOG_CATEGORIES] => 'blogCategory',
        self::SECTION_NAMES[self::ROLES] => 'roles',
        self::SECTION_NAMES[self::FAQ] => 'faqs',
        self::SECTION_NAMES[self::TESTIMONIALS] => 'testimonials',
        self::SECTION_NAMES[self::EMAIL_TEMPLATES] => 'emailTemplates',
        self::SECTION_NAMES[self::SMS_TEMPLATES] => 'smsTemplates',
        self::SECTION_NAMES[self::NOTIFICATION_TEMPLATES] => 'notificationTemplates',
        self::SECTION_NAMES[self::LANGUAGE_LABELS] => 'languageLabels',
        
        self::SECTION_NAMES[self::META_TAGS] => 'metaTags',
        self::SECTION_NAMES[self::URL_REWRITE] => 'urlRewrite',
        self::SECTION_NAMES[self::ALT_IMAGES] => 'imageAttributes',
        self::SECTION_NAMES[self::MISCELLANEOUS] => 'miscellaneous' ,
        
        self::SECTION_NAMES[self::ADMIN_USERS] => 'subAdmins',
        self::SECTION_NAMES[self::USERS] => 'users',
        self::SECTION_NAMES[self::USER_GROUPS] => 'userGroups',
        self::SECTION_NAMES[self::GDPR_REQUEST] => 'userRequests',

        self::SECTION_NAMES[self::SALE_REPORT] => 'saleReport',
        self::SECTION_NAMES[self::PROFIT_MARGIN_REPORT] => 'profitReport',
        self::SECTION_NAMES[self::ACQUISITION_REPORT] => 'acquisitionReport',
        self::SECTION_NAMES[self::BEHAVIOUR_REPORT] => 'behaviorReport',
        self::SECTION_NAMES[self::CUSTOMER_REPORT] => 'customerReport',

        self::SECTION_NAMES[self::LOCALIZATION] => 'businessManagement',
        self::SECTION_NAMES[self::TAX_SETTINGS] => 'tax',
        self::SECTION_NAMES[self::SHIPPING_FULFILMENT] => 'settingsShipping',
        self::SECTION_NAMES[self::PAYMENT_METHODS] => 'paymentMethods',
        self::SECTION_NAMES[self::PRODUCT_SETTINGS] => 'settingsProduct',

        self::SECTION_NAMES[self::SYSTEM_SETTINGS] => 'settingsLogo',
        self::SECTION_NAMES[self::THIRD_PARTY_INTEGRATION] => 'thirdpartyAnalytics',
        self::SECTION_NAMES[self::IMPORT_EXPORT] => 'importExportBrands',
        self::SECTION_NAMES[self::INVOICE_MANAGEMENT] => 'taxInvoice',

        self::SECTION_NAMES[self::SHIPPING_ZONE_RATE] => 'shipping',
        self::SECTION_NAMES[self::SHIPPING_PROFILE_DETAIL] => 'editShippingProfile',
        self::SECTION_NAMES[self::PICKUP_ADDRESS] => 'pickups',
        self::SECTION_NAMES[self::RETURN_AND_CANCELLATION] => 'returnCancellationMessages',

        self::SECTION_NAMES[self::ADD_PRODUCTS] => 'productCreate'
    ];
    protected $fillable = [
        'role_name','role_created_by_id','role_updated_by_id','role_created_at', 'role_updated_at'
    ];

    public static function getRoles($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = AdminRole::select('role_id', 'role_name');
        if (!empty($request['search'])) {
            $query->where('role_name', 'like', '%'. $request['search']. '%');
        }
        if ($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1) {
            return $query->latest('role_id')->paginate((int)$per_page);
        } else {
            return $query->latest('role_id')->paginate((int)$per_page, ['*'], 'page', (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
    }
    public function permissions()
    {
        return $this->hasMany('App\Models\Admin\AdminPermission', 'ap_role_id', 'role_id');
    }
    public static function getRolesList()
    {
        return AdminRole::select('role_id', 'role_name')->get();
    }
    public static function getRoleById($id)
    {
        return AdminRole::select('role_id', 'role_name', 'role_created_by_id', 'role_updated_by_id', 'role_created_at', 'role_updated_at')
                ->with(['permissions', 'createdAt', 'updatedAt'])->where('role_id', $id)->first();
    }

    public static function updatePermissions($roleId, $permissionsData)
    {
        AdminPermission::where('ap_role_id', $roleId)->delete();
        foreach ($permissionsData['role']  as $moduleId => $permissionId) {
            $moduleId = constant("self::$moduleId");
            AdminPermission::insert([
                'ap_role_id' => $roleId,
                'ap_section_id' => $moduleId,
                'ap_value' => $permissionId
            ]);
        }
    }

    public static function getPermissions($condition = 0)
    {
        $query = AdminRole::join("admin_permissions", 'role_id', 'ap_role_id')
                ->where('role_id', Auth::guard('admin')->user()->admin_role_id);
        if ($condition = 1) {
            $query->where('ap_value', '>=', 1);
        }
        return $query->pluck('ap_value', 'ap_section_id')->toArray();
    }

    public static function getUserPermissions()
    {
        $permissions = self::getPermissions();
        foreach ($permissions as $moduleId => $permission) {
            $userPermission['permissions'][ AdminRole::SECTION_NAMES[$moduleId] ] = $permission;
        }
        $userPermission['permissions']['role_id'] = Auth::guard('admin')->user()->admin_role_id;
        return json_encode($userPermission);
    }

    public static function getTabs()
    {
        return self::SECTIONS;
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'role_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'role_updated_by_id')->select(['admin_id', 'admin_username']);
    }
}
