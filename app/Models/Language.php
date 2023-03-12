<?php

namespace App\Models;

use App\Models\YokartModel;

class Language extends YokartModel
{
    public $timestamps = false;
    protected $fillable = [
        'lang_name', 'lang_code', 'lang_direction', 'lang_publish'
    ];
    protected $primaryKey = 'lang_id';

    public const EXCEPTIONS = [ /* to handle different lang codes in Google - MSN translators */
        'iw' => 'he'
    ];

    public const LANGUAGES = [
        [
            'lang_id' => 1,
            'lang_code' => 'en',
            'lang_name' => 'English',
            'lang_direction' => 'ltr',
            'lang_publish' => 1,
            'lang_default' => 1,
            'lang_view_default' => 1
        ],
        [
            'lang_id' => 2,
            'lang_code' => 'fr',
            'lang_name' => 'French',
            'lang_direction' => 'ltr',
            'lang_publish' => 1,
            'lang_default' => 0,
            'lang_view_default' => 0
        ],
        [
            'lang_id' => 3,
            'lang_code' => 'es',
            'lang_name' => 'Spanish',
            'lang_direction' => 'ltr',
            'lang_publish' => 1,
            'lang_default' => 0,
            'lang_view_default' => 0
        ],
        [
            'lang_id' => 4,
            'lang_code' => 'de',
            'lang_name' => 'German',
            'lang_direction' => 'ltr',
            'lang_publish' => 1,
            'lang_default' => 0,
            'lang_view_default' => 0
        ],
        [
            'lang_id' => 5,
            'lang_code' => 'ru',
            'lang_name' => 'Russian',
            'lang_direction' => 'ltr',
            'lang_publish' => 1,
            'lang_default' => 0,
            'lang_view_default' => 0
        ],
        [
            'lang_id' => 6,
            'lang_code' => 'hi',
            'lang_name' => 'Hindi',
            'lang_direction' => 'ltr',
            'lang_publish' => 1,
            'lang_default' => 0,
            'lang_view_default' => 0
        ],
        [
            'lang_id' => 7,
            'lang_code' => 'ar',
            'lang_name' => 'Arabic',
            'lang_direction' => 'rtl',
            'lang_publish' => 1,
            'lang_default' => 0,
            'lang_view_default' => 0
        ]
    ];

    public const PAGES = [
        'LOGIN' => 'Frontend||Login',
        'ACCOUNT_VERIFICATION' => 'Frontend||Account Verification',
        'SIGNUP' => 'Frontend||Signup',
        'ACCOUNT_COMPLETION' => 'Frontend||Account Completion',
        'FORGOT_PASSWORD' => 'Frontend||Forgot Password',
        'FAQS' => 'Frontend||FAQs',
        'PROFILE' => 'Frontend||Profile',
        'LOCALIZATION' => 'Frontend||Localization',
        'COOKIE_PREFERENCES' => 'Frontend||Cookie Preference',
        'PERSONALISATION' => 'Frontend||Personalisation',
        'PROFILE_SIDEBAR' => 'Frontend||Profile Sidebar',
        'ADDRESS_BOOK' => 'Frontend||Address Book',
        'SAVED_CARDS' => 'Frontend||Saved Cards',
        'SHARE_AND_EARN' => 'Frontend||Share & Earn',
        'REWARD_POINTS' => 'Frontend||Reward Points',
        'FAVORITES' => 'Frontend||Favorites',
        'REVIEWS' => 'Frontend||Reviews',
        'DISCOUNT_COUPONS' => 'Frontend||Discount Coupons',
        'MESSAGES' => 'Frontend||Messages',
        'DOWNLOADS' => 'Frontend||Downloads',
        'SAVED_PRODUCTS'=>'Frontend||Saved Products',
        'ORDERS' => 'Frontend||Orders',
        'PRODUCT_DETAIL' => 'Frontend||Product Detail',
        'PRODUCT_LISTING' => 'Frontend||Product Listing',
        'CMS' => 'Frontend||CMS',
        'CART' => 'Frontend||Cart',
        'SITEMAP' => 'Frontend||Sitemap',
        'CHECKOUT' => 'Frontend||Checkout',
        'NEWSLETTER' => 'Frontend||News Letter',
        'ORDER_SUCCESS' => 'Frontend||Order Success',
        'BLOG_LISTING' => 'Frontend||Blog Listing',
        'BLOG_DETAIL' => 'Frontend||Blog Detail',
        'BLOG_FOOTER' => 'Frontend||Blog Footer',
        'CMS_EDITOR' => 'Admin||CMS Editor',
        'ADMIN_MENU' => 'Admin||Menu',
        'ADMIN_QUICK_PANEL' => 'Admin||Quick panel',
        'ADMIN_MESSAGES' => 'Admin||Messages',
        'ADMIN_PROFILE' => 'Admin||Profile',
        'ADMIN_BRANDS' => 'Admin||Brands',
        'ADMIN_CATEGORIES' => 'Admin||Categories',
        'ADMIN_OPTION_GROUPS' => 'Admin||Option Groups',
        'ADMIN_PRODUCT_REVIEWS' => 'Admin||Product Reviews',
        'ADMIN_PRODUCTS' => 'Admin||Products',
        'ADMIN_ORDERS' => 'Admin||Orders',
        'ADMIN_COMMON' => 'Admin||Common',
        'ADMIN_404_NOTFOUND' => 'Admin||404 Page Not Found',
        'ADMIN_SPECIAL_PRICES' => 'Admin||Special Prices',
        'ADMIN_DISCOUNT_COUPONS' => 'Admin||Discount Coupons',
        'ADMIN_BUYTOGETHER_PRODUCTS' => 'Admin||Buy Together Products',
        'ADMIN_RELATED_PRODUCTS' => 'Admin||Related Products',
        'ADMIN_REWARD_POINTS' => 'Admin||Reward Points',
        'ADMIN_PAGES' => 'Admin||Pages',
        'ADMIN_BLOG_CATEGORIES' => 'Admin||Blog Categories',
        'ADMIN_BLOG_ARTICLES' => 'Admin||Blog Articles',
        'ADMIN_BLOG_COMMENTS' => 'Admin||Blog Comments',
        'ADMIN_FAQS' => 'Admin||FAQs',
        'ADMIN_TESTIMONIALS' => 'Admin||Testimonials',
        'ADMIN_EMAIL_TEMPLATES' => 'Admin||Email Templates',
        'ADMIN_SMS_TEMPLATES' => 'Admin||SMS Templates',
        'ADMIN_NOTIFICATION_TEMPLATES' => 'Admin||Notification Templates',
        'ADMIN_LANGUAGE_LABELS' => 'Admin||Language Labels',
        'ADMIN_META_TAGS' => 'Admin||Meta Tags',
        'ADMIN_URL_REWRITING' => 'Admin||Url Rewriting',
        'ADMIN_ALT_IMAGES' => 'Admin||Alt Images',
        'ADMIN_SEO_MISCELLANEOUS' => 'Admin||SEO Miscellaneous',
        'ADMIN_SUB_ADMINS' => 'Admin||Sub Admins',
        'ADMIN_ROLES' => 'Admin||Roles',
        'ADMIN_USER_GROUPS' => 'Admin||User Groups',
        'ADMIN_GDPR_REQUESTS' => 'Admin||GDPR Requests',
        'ADMIN_USERS' => 'Admin||Users',
        'ADMIN_REPORT_SALES' => 'Admin||Sales Report',
        'ADMIN_REPORT_PROFIT_MARGIN' => 'Admin||Profile Margin Report',
        'ADMIN_ACQUISITIONS_SALES' => 'Admin||Acquisitions Report',
        'ADMIN_BEHAVIOUR_SALES' => 'Admin||Behaviour Report',
        'ADMIN_CUSTOMERS_SALES' => 'Admin||Customers Report',
        'ADMIN_DASHBOARD' => 'Admin||Dashboard',
        'ADMIN_GET_STARTED' => 'Admin||Get Started',
        'ADMIN_LEFT_SIDEBAR' => 'Admin||Left Sidebar',
        'ADMIN_BUSINESS_INFORMATION' => 'Admin||Localization > Business Information',
        'ADMIN_DATE_TIME_UNITS' => 'Admin||Localization > Date , time & units',
        'ADMIN_LOCALIZATION_CURRENCIES' => 'Admin||Localization > Currencies',
        'ADMIN_LOCALIZATION_LANGUAGES' => 'Admin||Localization > Languages',
        'ADMIN_TAX' => 'Admin||Tax Management',
        'ADMIN_INVOICE' => 'Admin||Invoice Management',
        'ADMIN_PRODUCT_SETTINGS' => 'Admin||Product Settings',
        'ADMIN_SHIPPING_GENERAL' => 'Admin||Shipping & Fulfilment > General',
        'ADMIN_RETURN_CANCELLATION_REASONS' => 'Admin||Shipping & Fulfilment > Return & Cancellation Reasons',
        'ADMIN_PICKUP_ADDRESSES' => 'Admin||Shipping & Fulfilment > Pickup Addresses',
        'ADMIN_SHIPPING_ZONE_RATES' => 'Admin||Shipping & Fulfilment > Shipping Zones & Rates',
        'ADMIN_PAYMENT_METHODS' => 'Admin||Payment Methods',
        'ADMIN_PRODUCT_SETTINGS' => 'Admin||Product Settings',
        'ADMIN_SYSTEM_SETTINGS_LOGO' => 'Admin||System Settings > Logo',
        'ADMIN_SYSTEM_SETTINGS_CMS' => 'Admin||System Settings > CMS',
        'ADMIN_SYSTEM_SETTINGS_SMTP' => 'Admin||System Settings > Email(SMTP)',
        'ADMIN_SYSTEM_SETTINGS_NOTIFICATIONS' => 'Admin||System Settings > System Notifications',
        'ADMIN_SYSTEM_SETTINGS_SOCIAL' => 'Admin||System Settings > Social Networks',
        'ADMIN_SYSTEM_SETTINGS_COOKIES' => 'Admin||System Settings > Cookies',
        'ADMIN_SYSTEM_SETTINGS_SIDEBAR' => 'Admin||System Settings > Sidebar',
        'ADMIN_SYSTEM_SETTINGS_EMAIL_TEMPLATES' => 'Admin||System Settings > Email Templates',
        'ADMIN_SYSTEM_SETTINGS_THEMES' => 'Admin||System Settings > Themes',
        'ADMIN_THIRDPARTY_ANALYTICS' => 'Admin||Third Party Integrations > Analytics',
        'ADMIN_THIRDPARTY_CURRENCY' => 'Admin||Third Party Integrations > Currency',
        'ADMIN_THIRDPARTY_GEOLOCATION' => 'Admin||Third Party Integrations > Geo Location',
        'ADMIN_THIRDPARTY_EMAIL_MARKETING' => 'Admin||Third Party Integrations > Email Marketing',
        'ADMIN_THIRDPARTY_LANGUAGE' => 'Admin||Third Party Integrations > Language',
        'ADMIN_THIRDPARTY_LIVECHAT' => 'Admin||Third Party Integrations > Live Chat',
        'ADMIN_THIRDPARTY_SECURITY' => 'Admin||Third Party Integrations > Security',
        'ADMIN_THIRDPARTY_SMSTOOLS' => 'Admin||Third Party Integrations > SMS Tools',
        'ADMIN_THIRDPARTY_SOCIAL_LOGIN' => 'Admin||Third Party Integrations > Social Login Keys',
        'ADMIN_THIRDPARTY_SIDEBAR' => 'Admin||Third Party Integrations > Sidebar',
        'ADMIN_IMPORTEXPORT_SIDEBAR' => 'Admin||Import Export > Sidebar',
        'ADMIN_IMPORTEXPORT_BRANDS' => 'Admin||Import Export > Brands',
        'ADMIN_IMPORTEXPORT_CATEGORIES' => 'Admin||Import Export > Categories',
        'ADMIN_IMPORTEXPORT_OPTION_GROUPS' => 'Admin||Import Export > Option Groups',
        'ADMIN_IMPORTEXPORT_PHYSICAL_PRODUCTS' => 'Admin||Import Export > Physical Products',
        'ADMIN_IMPORTEXPORT_DIGITAL_PRODUCTS' => 'Admin||Import Export > Digital Products',
        'ADMIN_IMPORTEXPORT_SHIPPING_PACKAGES' => 'Admin||Import Export > Shipping Packages',
        'ADMIN_IMPORTEXPORT_MEDIA' => 'Admin||Import Export > Media',
        'ADMIN_MOBILE_APPS' => 'Admin||Mobile Apps',

        'ADMIN_THIRDPARTY_TRACKING_API' => 'Admin||Third Party Integrations > Tracking Api',

        'ADMIN_LOGIN' => 'Admin||Login',
        'ADMIN_FORGOT_PASSWORD' => 'Admin||Forgot Password',
        'ADMIN_RESET_PASSWORD' => 'Admin||Reset Password',
        'CATEGORIES' => 'Frontend||Categories Listing',
        'ADMIN_RESOURCES' => 'Admin||Resources',
        'MAINTENANCE_MODE' => 'Frontend||Maintenance mode',
        'OFFLINE_MODE' => 'Frontend||Off Line',
        'ERROR_PAGES' => 'Frontend||Error Pages'
    ];

    public static function checkException($langCode)
    {
        if (!empty(self::EXCEPTIONS[$langCode])) {
            return self::EXCEPTIONS[$langCode];
        }
        return $langCode;
    }

    public static function getRecords($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = Language::select('lang_id', 'lang_name', 'lang_code', 'lang_direction', 'lang_publish', 'lang_default', 'lang_view_default');

        if (!empty($request['search'])) {
            $query->where('lang_name', 'like', '%' . $request['search'] . '%');
        }
        if ($query->paginate((int) $per_page)->count() >= 1 && $query->paginate((int) $per_page)->currentPage() >= 1) {
            return $query->latest('lang_id')->paginate((int) $per_page);
        } else {
            return $query->latest('lang_id')->paginate((int) $per_page, ['*'], 'page', (int) $query->paginate((int) $per_page)->currentPage() - 1);
        }
    }

    public static function getAll()
    {
        return Language::where('lang_publish', 1)->latest('lang_view_default')
            ->pluck('lang_name', 'lang_code')->toArray();
    }

    public static function getDirections()
    {
        return Language::where('lang_publish', 1)->pluck('lang_direction', 'lang_code')->toArray();
    }

    public static function getDefault()
    {
        return Language::select('lang_id', 'lang_name', 'lang_code', 'lang_direction')->where('lang_default', 1)->first();
    }
    public static function getRecordById($id)
    {
        return Language::select('lang_code', 'lang_direction')->where('lang_id', $id)
            ->where('lang_publish', 1)->first();
    }

    public static function getLanguages($fields, $conditions)
    {
        $query = Language::select($fields);
        $query->where($conditions);
        return $query->get();
    }

    public static function getAllSystemLanguage()
    {
        return Language::LANGUAGES;
    }
}
