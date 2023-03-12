<?php

use App\Models\Language;
use App\Models\Currency;
use App\Models\AttachedFile;
use App\Models\Notification;
use App\Helpers\YokartHelper;
use App\Helpers\FileHelper;
use App\Helpers\ThemeHelper;
use App\Models\Configuration;
use App\Models\MetaTag;
use App\Models\AppPage;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminPermission;
use App\Models\UrlRewrite;
use App\Models\Order;
use App\Models\Product;
use App\Models\Package;
use App\Models\CouponHistory;
use App\Models\ProductCategory;
use App\Models\Admin\Todo;
use App\Models\ThreadMessage;
use App\Models\ProductStockHold;
use App\Models\ProductOptionVarient;
use App\Models\OrderReturnRequest;
use App\Models\ProductReview;
use App\Models\Brand;
use App\Models\Timezone;
use App\Models\Theme;
use App\Models\ProductOptionName;
use App\Models\DiscountCoupon;
use App\Models\DiscountCouponRecord;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

const PERMISSION_READ = 'PERMISSION_READ';
const PERMISSION_WRITE = 'PERMISSION_WRITE';
const CONSTANTS = 'constants';

require_once(__DIR__ . '/YokartBaseHelper.php');

ini_set('memory_limit', '200M');

//function to check if admin user's role have read permission to a section

function apiresponse($statusCode, $message = '', $data = [], $common = [])
{
    $response = [];
    $response['status'] = $statusCode;
    $response['message'] = $message;

    if (!empty(Auth::guard('api')->user()->user_currency_id)) {
        $currency = Currency::getRecordById(Auth::guard('api')->user()->user_currency_id);
    } else {
        $currency = Currency::getDefault();
    }
    $currency = !empty($currency) ? $currency->toArray() : [];
    $currency['decimal_values'] = getConfigValueByName('DECIMAL_VALUES') == 1 ? true : false;
    $messageCount = 0;
    if (!empty(Auth::guard('api')->user()->user_id)) {
        $messageCount = ThreadMessage::getUserUnreadMessageCount(Auth::guard('api')->user()->user_id);
    }

        $cart_qty = \Cart::getCount();

    $data['common'] = [
        'notification_count' => 0,
        'msg_count' => $messageCount,
        'cart_qty' => $cart_qty,
        'currency' => Arr::only($currency, ['currency_id', 'currency_code', 'currency_symbol', 'currency_position', 'currency_value','decimal_values'])
        
    ];

   // var_dump(getConfigValueByName('DECIMAL_VALUES'));
 //   $data['common']['currency']['decimal_values'] = getConfigValueByName('DECIMAL_VALUES') == 1 ? true : false;
    if (count($common) > 0) {
        $data['common'] = array_merge($common, $data['common']);
    }
    
    $response['data'] = $data;
 
    return response($response, $statusCode);
}

function canRead($module, $userRole = 0, $json = false)
{
    if ($userRole == 0) {
        $userRole = Auth::guard('admin')->user()->admin_role_id;
    }

    /*if(!authorise($module, $userRole, PERMISSION_READ) && $json)
    {
        exit(jsonresponse(false, __('Unauthorised access'), []));
    }*/
    return authorise($module, $userRole, PERMISSION_READ);
}
// function to check if admin user's role have write permission to a section

function canWrite($module, $userRole = 0, $json = false)
{
    if ($userRole == 0) {
        $userRole = Auth::guard('admin')->user()->admin_role_id;
    }

    /*if(!authorise($module, $userRole, PERMISSION_WRITE) && $json )
    {
        exit(jsonresponse(false, __('Unauthorised access'), []));
    }*/
    return authorise($module, $userRole, PERMISSION_WRITE);
}

function authorise($module, $userRole, $permission)
{
    if ($userRole === null) {
        // allowing superadmin to have full access at all times
        return true;
    }

    $permissionExists = AdminPermission::where('ap_role_id', $userRole)
        ->where('ap_section_id', $module)
        ->where('ap_value', '>=', config(CONSTANTS . '.' . $permission))
        ->count();

    if ($permissionExists > 0) {
        //if user has the required permission to requested module
        return true;
    }
    return false;
}

function executeCurl($url, $data = [], $headers = [])
{
    $curl = curl_init();

    if (!empty($headers)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }

    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    curl_close($curl);

    return json_decode($json, true);
}


/* get notifications sidebar data*/
function notifications()
{
    $data = [];
    $data['notifications'] = Notification::getNotifications();
    $data['total'] = Notification::getNotificationCount();
    $data['unread'] = Notification::getUnreadNotificationCount();
    return $data;
}

/** get all notification + todo + messagecount */
function allNotificationsCount()
{
    $data['count'] = 0;
    $data['count'] = Notification::getUnreadNotificationCount() + Todo::getTodoNotificationCount() + ThreadMessage::getMessageCount();
    return $data;
}
/* function to create slug from string */
function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // trim
    $text = trim($text, '-');
    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // lowercase
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

function currencyFromSession()
{
    $currency = session()->get('currency');
    return [
        'currency_code' => ($currency['currency_code'] ?? ''),
        'currency_symbol' => ($currency['currency_symbol'] ?? ''),
        'currency_position' => ($currency['currency_position'] ?? ''),
        'currency_value' => ($currency['currency_value'] ?? 1)
    ];
}

function currencySymbol()
{
    $currentCurrency = currencyFromSession();
    return $currentCurrency['currency_symbol'];
}

function currencyValue()
{
    $currentCurrency = currencyFromSession();
    return $currentCurrency['currency_value'];
}

function currencyCode()
{
    $currentCurrency = currencyFromSession();
    return $currentCurrency['currency_code'];
}

function priceByCurrency($price, $exchangeRate)
{
    return $price * $exchangeRate;
}

function convertDefaultCurrency($price)
{
    $currentCurrency = currencyFromSession();
    return  $price / $currentCurrency['currency_value'];
}
function displayPrice($price, $displaySymbol = true, $format = true, $fromEditor = false)
{
    if ($fromEditor == false) {
        $currentCurrency = currencyFromSession();

        $price = $price * $currentCurrency['currency_value'];
    }

    if ($format == true) {
        $price =  priceFormat($price);
    }

    if ($displaySymbol == true) {
        if ($fromEditor) {
            $price = '$' . $price;
        } else {
            if ($currentCurrency['currency_position']) {
                $price = $price . $currentCurrency['currency_symbol'];
            } else {
                $price = $currentCurrency['currency_symbol'] . $price;
            }
        }
    }
    return $price;
}

function formatPriceByCurrencyCode($price, $currencyCode)
{
    $currency = Currency::getRecordByCode($currencyCode);
    if (!empty($currency)) {
        if ($currency->currency_position) {
            $price = $price . $currency->currency_symbol;
        } else {
            $price = $currency->currency_symbol . $price;
        }
    } else {
        $price = $currencyCode . $price;
    }
    return $price;
}

function cartesianArray($input)
{
    $result = array(array());
    foreach ($input as $key => $values) {
        $append = array();
        foreach ($result as $product) {
            foreach ($values as $item) {
                $product[$key] = $item;
                $append[] = $product;
            }
        }
        $result = $append;
    }

    return $result;
}

function priceFormat($value, $numberFormat = true)
{
    if (getConfigValueByName('DECIMAL_VALUES') == 0) {
        if ($numberFormat == true) {
            return number_format($value);
        }
        return round($value);
    }
    $config = config('shopping_cart');
    if ($numberFormat == true) {
        return number_format($value, $config['decimals'], $config['dec_point'], $config['thousands_sep']);
    }
    return round($value, $config['decimals']);
}
function custom_number_format($n, $precision = 3)
{
    if ($n < 1000) {
        // Anything less than a thousand
        $n_format = number_format($n);
    } elseif ($n < 1000000) {
        // Anything less than a million
        $n_format = number_format($n / 1000, $precision) . 'K';
    } elseif ($n < 1000000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, $precision) . 'M';
    } else {
        // At least a billion
        $n_format = number_format($n / 1000000000, $precision) . 'B';
    }

    return $n_format;
}

function imageUpload($request, $id, $constantType, $subRecordId = 0, $deviceType = 0)
{
    $record = 0;
    if ($request->hasFile('cropImage')) {
        if ($request->hasFile('actualImage')) {
            $uploadedFileName = FileHelper::uploadFile($request->file('actualImage'));
        } else {
            $recordData = AttachedFile::getAttachedFile(AttachedFile::getConstantVal($constantType), $id, $subRecordId, $deviceType);
            $uploadedFileName = $recordData->afile_physical_path;
        }
        
        if ($request->hasFile('cropImage')) {
            if (strpos($uploadedFileName, '-thumb') == true) {
                $uploadedFileName = str_replace('-thumb', '', $uploadedFileName);
            }
            $uploadedFileName = FileHelper::uploadThumbFile($uploadedFileName . '-thumb', $request->file('cropImage'));
        }
       
        if ($request->hasFile('actualImage')) {
            $record =  AttachedFile::saveOrUpdateFile(AttachedFile::getConstantVal($constantType), $id, $uploadedFileName, $request->file('actualImage')->getClientOriginalName(), $subRecordId, $deviceType);
        } else {
            $record = AttachedFile::where('afile_type', AttachedFile::getConstantVal($constantType))->where('afile_record_id', $id)->where('afile_record_subid', $subRecordId)
                ->where('afile_device', $deviceType)
                ->first();
            $record->touch();
        }
    }
    return $record;
}

function getFileUrl($section, $recordId, $subRecordId = 0, $size = 'original')
{
    return AttachedFile::getFileUrl($section, $recordId, $subRecordId, $size);
}

/** Function will get all the file images url*/
function getAllAttachedFiles($section, $recordId, $subRecordId = 0, $size = 'original')
{
    $imagesUrl = array();
    $attachedImages = AttachedFile::getBulkFiles(AttachedFile::getConstantVal($section), $recordId);
    if (isset($attachedImages) && !empty($attachedImages)) {
        foreach ($attachedImages as $key => $images) {
            $imagesUrl[$key] = url('image/' . $images->afile_id . '/thumb');
        }
    }
    return $imagesUrl;
}

function fileExists($section, $recordId, $subRecordId = 0)
{
    return AttachedFile::getAttachedFileCount($section, $recordId, $subRecordId);
}

function getFrontendLogo()
{
    $logo = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('frontendLogo'), 0, 0, 'thumb');
    if (!empty($logo)) {
        return url('/') . '/yokart/image/' . $logo->afile_id . '/thumb?t=' . strtotime($logo->afile_updated_at);
    }
    return '';
}

function getFrontendDarkLogo()
{
    $darkLogo = AttachedFile::getFileUrl('frontendDarkModeLogo', 0, 0, 'thumb');
    if (!empty($darkLogo)) {
        return $darkLogo;
    }
    return '';
}

function getFrontendLogoHtml()
{
    $configurations = getConfigValues([
        'FRONTEND_LOGO_RATIO',
    ]);
    $logo = AttachedFile::getFileUrl('frontendLogo', 0, 0, 'thumb');
    $darkLogo = AttachedFile::getFileUrl('frontendDarkModeLogo', 0, 0, 'thumb');
    if (empty($logo)) {
        $logo = noImage("4_1/160x40.png");
    }
    return view('themes.' . config('theme') . '.shortcodes.logo', compact('logo', 'darkLogo'))->with('ratio', $configurations['FRONTEND_LOGO_RATIO'])->render();
}

function getHeader()
{
   
   
        $configurations = getConfigValues([
            'FRONTEND_LOGO_RATIO',
            'HEADER_HTML',
            'DARK_MODE_ENABLE'
        ]);
        
    
        $headerContent = json_decode($configurations['HEADER_HTML'], true);
        $headerHtml = isset($headerContent['gjs-html']) ? $headerContent['gjs-html'] : $configurations['HEADER_HTML'];
    
        //Login
        $loginHtml = view('themes.' . config('theme') . '.shortcodes.login')->render();
        $headerHtml = preg_replace('/<login-shortcode(.*?)<\/login-shortcode>/s', $loginHtml, $headerHtml, 1);
    
        //Logo
    
        $logoHtml = getFrontendLogoHtml();
        $headerHtml = preg_replace('/<logo-shortcode(.*?)<\/logo-shortcode>/s', $logoHtml, $headerHtml, 1);
    
        //LanguageAndCurrency
        $languagecurrencyHtml = view('themes.' . config('theme') . '.shortcodes.languageCurrency')->render();
        $headerHtml = preg_replace('/<languagecurrency-shortcode(.*?)<\/languagecurrency-shortcode>/s', $languagecurrencyHtml, $headerHtml, 1);
    
        //Search
        $searchHtml = view('themes.' . config('theme') . '.shortcodes.search')->render();
        $headerHtml = preg_replace('/<searchinput-shortcode(.*?)<\/searchinput-shortcode>/s', $searchHtml, $headerHtml, 1);
    
        //darkmode
        $darkmodeHtml = "";
        if ($configurations['DARK_MODE_ENABLE'] == 1) {
            $darkmodeHtml = view('themes.' . config('theme') . '.shortcodes.darkMode')->render();
        }
        $headerHtml = preg_replace('/<darkmode-shortcode(.*?)<\/darkmode-shortcode>/s', $darkmodeHtml, $headerHtml, 1);
    
        //navmenu
        $themeClass = 'App\Helpers\\' . ucwords(config('theme')) . 'Helper';
        $headerHtml = $themeClass::loadNavigationLayout1($headerHtml, 1);
    
        //cart
        $cartHtml = view('themes.' . config('theme') . '.shortcodes.cart')->render();
        $headerHtml = preg_replace('/<cart-shortcode(.*?)<\/cart-shortcode>/s', $cartHtml, $headerHtml, 1);
    
        $headerHtml = str_replace('BASE_URL', url('') . '/', $headerHtml);
        $headerHtml = $themeClass::removeGrapesjsTags($headerHtml);
        return str_replace('BASE_URL', url(''), $headerHtml);
    
}

function getFooter()
{
    return \Cache::rememberForever('footer-section', function () {
        
        $footerHtml = getConfigValueByName('FOOTER_HTML');
        $footerContent = json_decode($footerHtml, true);
        $footerHtml = isset($footerContent['gjs-html']) ? $footerContent['gjs-html'] : $footerHtml;

        //Newsletter
        $footerHtml = str_replace('newsletter-action=""', 'method="POST" action="' . route("newsletterPost") . '"', $footerHtml);

        $footerHtml = str_replace('BASE_URL', url('') . '/', $footerHtml);
        $themeClass = 'App\Helpers\\' . ucwords(config('theme')) . 'Helper';
        $footerHtml = $themeClass::removeGrapesjsTags($footerHtml);
        return str_replace('BASE_URL', url(''), $footerHtml);
    });
}

function getControllerAction($routeName = null)
{
    if ($routeName != null) {
        $route = \Route::getRoutes()->getByName($routeName);
    } else {
        $route = \Route::getCurrentRoute();
    }
    $routeArray = $route->getAction();
    $controllerAction = class_basename($routeArray['controller']);
    list($controller, $action) = explode('@', $controllerAction);
    return ['controller' => $controller, 'action' => $action];
}

function getRewriteUrl($type, $recordId, $addBaseurl = true)
{
    $url = UrlRewrite::getUrl($type, $recordId);
    if ($addBaseurl) {
        $url = url('') . '/' . $url;
    }
    return $url;
}

function newUrl($rewrite_url)
{
    return UrlRewrite::getByOriginalUrl($rewrite_url);
}

function getPageByType($type)
{
    $page = \App\Models\Page::getPageByType($type);
    return getRewriteUrl('pages', $page->page_id);
}

function getMetaTags($record_id = null)
{
    $routeData = getControllerAction();
    $metaTags = MetaTag::getMetaTags($routeData['controller'], $routeData['action'], $record_id);
    if (empty($metaTags)) {
        $routeData = getControllerAction('showPage');
        $metaTags = MetaTag::getMetaTags($routeData['controller'], $routeData['action']);
    }
    return $metaTags;
}
function getDefalutMetaTags()
{
    return MetaTag::whereNull('meta_record_id')->where(['meta_controller' => 'HomeController', 'meta_action' => 'pageRender', 'meta_record_id' => 1])->first();
}

function getUserPermissions()
{
    return AdminRole::getUserPermissions();
}

function getGlobalSettings()
{
    return Configuration::getDefaultSettings();
}

function productStockVerify($product, $cartQty = 0, $varientCode = 0)
{
    if (is_array($product) == false) {
        $product = $product->toArray();
    }
    $stock = true;
    if ($product['prod_stock_out_sellable'] == 0) {
        if ($product['totalstock'] < $product['prod_min_order_qty']) {
            $stock = false;
        }
        if ($cartQty != 0 && $product['totalstock'] < $cartQty) {
            $stock = false;
        }

        $holdStock = ProductStockHold::ValidateHoldStock($product['prod_id'], $varientCode);
        if ($product['totalstock'] <  $cartQty + $holdStock) {
            $stock = false;
        }
    }

    return $stock;
}
function getFileSize($file_path)
{
    return Storage::disk('uploads')->size($file_path);
}
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}
function productColorId($product)
{
    $subRecordId  = 0;

    if (!empty($product->colorOptions) && $product->colorOptions->count() > 0 && !empty($product->pov_code)) {
        $record = $product->colorOptions->whereIn('poption_opn_id', explode('|', $product->pov_code))->first();

        if ($record) {
            $subRecordId = $record->poption_id;
        }
    }

    return $subRecordId;
}

function getLanguages()
{
    return Language::getAll();
}
function getDefaultLanguage()
{
    return Language::where(['lang_publish' => config('constants.YES'), 'lang_view_default' => config('constants.YES')])->first();
}

function getLanguagesDirection()
{
    return Language::getDirections();
}

function getLanguageById($id)
{
    return Language::getRecordById($id);
}

function getCurrencies()
{
    return Currency::getActiveCurrencies();
}

function getSystemCurrency()
{
    return Currency::getSystemDefault();
}

/** ways to use imageConfig function
 * $imagesConfig = imageConfig("DISCOUNT_COUPON.ASPECTRATIO"); for specific setting
 * $imagesConfig = imageConfig(["DISCOUNT_COUPON"]); for specific module
 * $imagesConfig = imageConfig(); for all
 */
function imageConfig($keys = [])
{
    if (\Cache::has('config-images')) {
        $config = \Cache::get('config-images');
    } else {
        $config = \Cache::rememberForever('config-images', function () {
            return Configuration::getImageConfigurations();
        });
    }
    if (is_string($keys)) {
        return Arr::dot($config)[$keys];
    }
    if (empty($keys)) {
        return $config;
    }
    return array_intersect_key($config, array_flip($keys));
}
function getConfigValues($keys)
{
    $value = \Cache::get('configuration');
    if (!empty($value) && $value != null) {
        $value = $value->toArray();
    } else {
        $value = [];
    }
    return array_intersect_key($value, array_flip($keys));
}
function getConfigValueByName($key)
{
    $value = \Cache::get('configuration');
    if (isset($value[$key])) {
        return $value[$key];
    }
    return null;
}
function shareThis()
{
    return Configuration::getRecordsByPrefix('SHARETHIS_', true);
}
function productReturnVerify($orderStatus, $orderDate, $product)
{
    $adddays = Carbon::parse($orderDate)->addDays($product->op_return_age);
    if ($orderStatus == Order::SHIPPING_STATUS_SHIPPED) {
        return  ['status' => false, 'message' => 'This product is under shipped, return Not available'];
    } elseif ($orderStatus == Order::SHIPPING_STATUS_DELIVERED && Carbon::parse(Carbon::now())->gt($adddays)) {
        return  ['status' => false, 'message' => 'Return Not available'];
    } elseif (empty(in_array($orderStatus, [Order::SHIPPING_STATUS_IN_PROGRESS, Order::SHIPPING_STATUS_READY_FOR_SHIPPING])) && $product->op_product_type == Product::DIGITAL_PRODUCT) {
        return  ['status' => false, 'message' => 'Return Not available, Due to digital order'];
    } elseif (!empty($product->cancelRequest)) {
        return  ['status' => false, 'message' => 'Your return status is ' . OrderReturnRequest::REQUEST_STATUS[$product->cancelRequest->orrequest_status]];
    }
    return ['status' => true];
}

function avgRating($productId)
{
    $record = [];
    $record['rating'] = ProductReview::getAverageRatingByProductId($productId);
    $record['count'] = ProductReview::getReviewCount($productId);
    return $record;
}

function cartCount()
{
    return Cart::cartItemsCount();
}
function orderStatusVerify($orderStatus)
{
    $status = '';
    if ($orderStatus == Order::ORDER_STATUS_RETURNED || $orderStatus == Order::ORDER_STATUS_PARTIAL_RETURNED) {
        $status  = Order::ORDER_STATUS[$orderStatus];
    }
    return  $status;
}
function isOrderPaid($orderStatus)
{
    $status = false;
    if ($orderStatus == Order::PAYMENT_PAID) {
        $status  = true;
    }
    return  $status;
}
function calculateRefund($orderId, $paid = true)
{
    $returns = Order::getReturnByOrderId($orderId);
    $total = 0;
    $shipping = 0;
    foreach ($returns as $return) {
        if ($return->orrequest_order_status == 0 && $paid == true) {
            continue;
        }
        $totaltax = $return->tax->opc_value * $return->orrequest_qty;
        $total += $return->op_product_price * $return->orrequest_qty + $totaltax;
    }
    return round($total + $shipping, 2);
}
function getProductImages($prodId, $varientId = 0, $allImages = false, $size = '', $type = 'web')
{
   
    $productImages = [];
    if ($varientId != 0) {
        $productImages = AttachedFile::getBulkFiles(AttachedFile::getConstantVal('productImages'), $prodId, $varientId, $allImages, true, $size, $type);
    }
    if (count($productImages) == 0) {
        $productImages = AttachedFile::getBulkFiles(AttachedFile::getConstantVal('productImages'), $prodId, 0, $allImages, $size, true, $type);
    }
    return $productImages;
}
function productImageUrl($prodId, $varientCode, $size = "")
{
    $size = !empty($size) ? $size : getProdSize("medium");
    $varientCode = !empty($varientCode) ? $varientCode : 0;
    return url("/") . "/yokart/product/image/" . $prodId . "/" . $varientCode . "/" . $size . "?t=" . time();
}
function returnOrderStatus($order, $type)
{
    $status = [];
    foreach ($order->products as $product) {
        if ($product->cancelRequest && OrderReturnRequest::flipedStatus()[$type] == $product->cancelRequest->orrequest_status) {
            $status[] = true;
        } else {
            $status[] = false;
        }
    }

    if (count($status) > 0 && empty(in_array(false, $status))) {
        return true;
    }
    return false;
}

function getAdminImageData()
{
    $data = [];
    $admin = Admin::getAdminData();
    $data['admin_id'] = $admin->admin_id;
    $data['admin_name'] = $admin->admin_name;
    $data['admin_name_badge'] = substr($admin->admin_name, 0, 1);
    $data['admin_image'] = !empty($admin->afile_id) ? url('') . '/yokart/image/profileImage/' . $admin->admin_id . '/0/thumb/' . time() : '';
    return $data;
}

function getAdminLogo()
{
    $logo = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('adminLogo'), 0);
    if (!empty($logo)) {
        return url('') . '/yokart/image/' . $logo->afile_id . '/thumb?t' . strtotime($logo->afile_updated_at);
    }
    return '';
}

function createUrl($urlRewrite)
{
    if(!$urlRewrite){
        return;
    }
    if (!empty($urlRewrite->urlrewrite_custom)) {
        return url('/' . $urlRewrite->urlrewrite_custom);
    }
    return url('/' . $urlRewrite->urlrewrite_original);
}

function isVariantExist($allVariants, $selectedVariant)
{
    foreach ($allVariants as $variants) {
        $variant = array_filter(explode('|', $variants));
        unset($variant[0]);
        if (in_array($selectedVariant, $variant)) {
            return true;
            exit;
        }
    }
    return false;
}
function getImageByVariantCode($productId, $variantCode)
{
    $fields =  'prod_id,pov_code, ' . Product::getPrice();
    $product = Product::productById($productId, $fields, [], $variantCode);

    return productColorId($product);
}

function getConvertedAdminDateTime($dateTime)
{
    return Timezone::getConvertedAdminDateTime($dateTime);
}

function getConvertedDateTime($dateTime, $time = true)
{
    return Timezone::getConvertedDateTime($dateTime, $time);
}
function getCategoriesNames($ids)
{
    return ProductCategory::getRecordsByIds($ids);
}

function getConvertedTime($dateTime)
{
    return Timezone::getConvertedTime($dateTime);
}
function formatTime($time)
{
    return Timezone::formatTime($time);
}

function getBrandNames($ids)
{
    return Brand::getRecordsByIds($ids);
}
function getOptionNames($ids)
{
    return ProductOptionName::getRecordsByIds($ids);
}
function getProductConditions()
{
    return Product::PRODUCT_CONDITIONS;
}

function getGetStartedStatus()
{
    return Admin::moduleStats();
}

function parseYouTubeurl($url)
{
    preg_match("/(?:[\/]|v=)([a-zA-Z0-9-_]{11})/", $url, $matches);

    return (isset($matches[1])) ? $matches[1] : false;
}

function getProductfavorite($product)
{
    if ($product->favId && ($product->ufp_pov_code = "" || $product->ufp_pov_code == $product->pov_code)) {
        return true;
    }
    return false;
}

function validateCoupon($couponId)
{
    return DiscountCouponRecord::validCouponByUserId($couponId, Auth::user()->user_id);
}
function getCouponSummary($couponId)
{
    return DiscountCoupon::couponSummary($couponId, Auth::user()->user_id);
}
function loginPopup()
{
    $smsPackageCheck = Package::getPublishedPackages('sms');
    $smsActivePackage = $smsPackageCheck->count();
    $defaultCountry = getDefaultCountryCode();
    return view('themes.' . config('theme') . '.partials.loginPopup', compact('smsActivePackage', 'defaultCountry'))->render();
}

function getDefaultCountryCode()
{
    $ip = (\Request::ip() != "::1") ? \Request::ip() : config('app.LOCAL_IP');
    $defaultCountry = 'us';
    if (!empty($ip)) {
        $locationData = \Location::get($ip);
        if (!empty($locationData->countryCode)) {
            $defaultCountry = strtolower($locationData->countryCode);
        }
    }
    return $defaultCountry;
}

function validateShippigType($productType, $shipType)
{
    if ($shipType == 'shipping') {
        if (in_array(3, $productType) || in_array(1, $productType) || in_array(0, $productType)) {
            return true;
        }
    } else {
        if (!in_array(0, $productType) && (in_array(3, $productType) || in_array(2, $productType))) {
            return true;
        }
    }
}
function validateEntireShippigType($productType, $shipType)
{
    if ($shipType == 'shipping' && in_array(2, $productType)) {
        return false;
    } elseif ($shipType == 'pickup' && (in_array(1, $productType)  || in_array(0, $productType))) {
        return false;
    }
    return true;
}

function cleanString($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function cleanSpaces($string)
{
    $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.


    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function clearStorageFolder($directory)
{
    $files =   Storage::allFiles($directory);
    if (!empty($files)) {
        Storage::delete($files);
    }
}
function validateDigitalProduct($order)
{
    if ($order->payment_status == Order::PAYMENT_PAID && $order->order_shipping_status ==  Order::ORDER_STATUS_COMPLETED) {
        return true;
    }
    return false;
}

function getActiveThemeSlug()
{
    $theme = Theme::getActiveTheme();
    return !empty($theme->theme_slug) ? $theme->theme_slug : config('constants.THEME');
}


function generateNumericOTP($n)
{
    $generator = "1357902468";
    $result = "";
    for ($i = 1; $i <= $n; $i++) {
        $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }
    // Return result
    return $result;
}

function isValidCoupon($coupon, $cartData)
{
    $userId = 0;
    if (Auth::check()) {
        $userId = Auth::user()->user_id;
    }
  
    $products = [];
    if (!empty($cartData)) {
        foreach ($cartData as $key => $collection) {
            $fields =  'prod_id, brand_id,cat_id, ' . Product::getPrice();

            $code = $key;
            if ($collection->product_id ===  $key) {
                $code = 0;
            }
            $products[$key] = Product::productById($collection->product_id, $fields, [], $code);
        }
    }

    $discountTypes = DiscountCouponRecord::DISCOUNT_RECORD_TYPE;
    $couponExist = [];

    foreach ($products as $productKey => $product) {
        foreach ($discountTypes as $key => $discountType) {
            $conditionobj = $coupon->couponConditions->where('dcr_type', $key);

            switch ($discountType) {

                case 'users':

                    if ($conditionobj->count() > 0) {
                        if (Auth::check() && $conditionobj->where('dcr_record_id', $userId)->count() > 0) {
                            $couponExist["users"][] = true;
                        } else {
                            $couponExist["users"][] = false;
                        }
                    }

                    break;
                case 'products':
                    if ($conditionobj->count() > 0) {
                        $records = $conditionobj->where('dcr_record_id', $product['prod_id'])->pluck('dcr_subrecord_id')->toArray();

                        if (count($records) > 0) {
                            $code = $productKey;
                            if ($code != 0 && substr($code, -1) != "|") {
                                $code = $code . '|';
                            }

                            if (in_array(0, $records) || in_array($code, $records)) {
                                $couponExist["products"][] = true;
                            } else {
                                $couponExist["products"][] = false;
                            }
                        }
                    }


                    break;
                case 'brands':
                    if ($conditionobj->count() > 0) {
                        if ($conditionobj->where('dcr_record_id', $product['brand_id'])->count() > 0) {
                            $couponExist["brands"][] = true;
                        } else {
                            $couponExist["brands"][] = false;
                        }
                    }

                    break;
                case 'categories':
                    if ($conditionobj->count() > 0) {
                        if ($conditionobj->where('dcr_record_id', $product['cat_id'])->count() > 0) {
                            $couponExist["categories"][] = true;
                        } else {
                            $couponExist["categories"][] = false;
                        }
                    }

                    break;
            }
        }
    }

    if (count($couponExist)  > 0) {
        if ($coupon->discountcpn_operator == DiscountCoupon::AND_CONDITION) {
            $condition = [];
            foreach ($couponExist as $key => $coupon) {
                if (in_array(true, $coupon)) {
                    $condition[] = true;
                }
            }
            if (count($condition) == count($couponExist)) {
                return true;
            } else {
                return false;
            }
        } else {
            if ((isset($couponExist['categories']) && in_array(true, $couponExist['categories'])) ||
                (isset($couponExist['brands']) && in_array(true, $couponExist['brands']))  ||
                (isset($couponExist['products']) && in_array(true, $couponExist['products'])) ||
                (isset($couponExist['users']) && in_array(true, $couponExist['users']))
            ) {
                return true;
            }
        }
    } else {
        return true;
    }
}

function isExpiredCoupon($coupon)
{
    $userId = 0;
    if (Auth::check()) {
        $userId = Auth::user()->user_id;
    }
    if (empty(CouponHistory::CouponHistoryValidate($coupon, $userId))) {
        return false;
    }
    return true;
}
function calCulateOrderSubtotal($order)
{
    $shippingPrice = 0;
    $taxPrice = 0;
    $discountPrice = 0;
    $rewardPrice = 0;
    $giftPrice = 0;
    if (!empty($order->order_shipping_value)) {
        $shippingPrice = $order->order_shipping_value;
    }
    if (!empty($order->order_tax_charged)) {
        $taxPrice = $order->order_tax_charged;
    }
    if (!empty($order->order_discount_amount)) {
        $discountPrice = $order->order_discount_amount;
    }
    if (!empty($order->order_reward_amount)) {
        $rewardPrice = $order->order_reward_amount;
    }
    if (!empty($order->order_gift_amount)) {
        $giftPrice = $order->order_gift_amount;
    }
    return $order->order_net_amount - $taxPrice - $shippingPrice - $giftPrice + $discountPrice + $rewardPrice;
}
function productColorVarients($product)
{
    $varientCodes = [];
    if (!empty($product->colorOptions) && $product->colorOptions->count() > 0) {
        foreach ($product->colorOptions as $option) {
            if (ProductOptionVarient::where(['pov_prod_id' => $option->poption_prod_id, 'pov_publish' => config('constants.YES')])->where('pov_code', 'LIKE', '%|' . $option->poption_opn_id . '|%')->exists()) {
                $varientCodes[$option->opn_value] = ($option->opn_color_code) ?? $option->opn_value;
            }
        }
    }
    return $varientCodes;
}
function getLogo()
{
    $logo = AttachedFile::getFileUrl('frontendLogo', 0, 0, 'thumb');
    if (empty($logo)) {
        $logo = noImage("4_1/160x40.png");
    }
    return $logo;
}
function getFavicon()
{
    $favicon = getFileUrl('favicon', 0, 0, '48-48');
    if (empty($favicon)) {
        $favicon = noImage("1_1/48x48.png");
    }
    return $favicon;
}

function productAspectRatio()
{
    return Product::PRODUCT_MEDIA_TYPE[getConfigValueByName('MEDIA_TYPES')];
}

function productDummyImage($fullPath = true)
{
    $aspectRatio = Product::PRODUCT_MEDIA_TYPE[getConfigValueByName('MEDIA_TYPES')];
    $pixels = Product::PRODUCT_MEDIA_PIXELS[getConfigValueByName('MEDIA_TYPES')];
    $aspectRatioDir = str_replace(":", "_", $aspectRatio);
    if ($fullPath === true) {
        return noImage($aspectRatioDir . '/' . $pixels . '.png');
    }
    return $aspectRatioDir . '/' . $pixels . '.png';
}

function isSavedCardsActive()
{
    return Package::where('pkg_publish', config('constants.YES'))->where('pkg_card_type', config('constants.YES'))
        ->where('pkg_type', Package::PKG_TYPE['paymentGateways'])->where('pkg_slug','Stripe')
        ->count();
}
function productPerPageListingRecords()
{
    if (getConfigValueByName('LISTING_GRID_DEFAULT') == 4) {
        return Product::FOUR_COLUMN_PER_PAGE_RECORD;
    }
    return Product::FIVE_COLUMN_PER_PAGE_RECORD;
}

function obfuscate($data)
{
    $clear = strpos($data, "@");
    if ($clear === false) {
        $clear = max(0, strlen($data) - 2);
    }
    $hide = max(0, min($clear - 1, 1));
    return substr($data, 0, $hide) .
        str_repeat("x", $clear - $hide) .
        substr($data, $clear);
}
function defaultFlag()
{
    $userDefaultFlag = '';
    $ip = (\Request::ip() != "::1") ? \Request::ip() : config('app.LOCAL_IP');
    if (!empty($ip)) {
        $locationData = \Location::get($ip);
        if (!empty($locationData)) {
            $countryCode = $locationData->countryCode;
            $userDefaultFlag = strtolower($countryCode);
            if (!empty(Auth::user()->user_id)) {
                if (empty(Auth::user()->user_country_id)) {
                    $country = App\Models\Country::getCountries(['country_id'], ['country_code' => $countryCode])->first();
                    App\Models\User::where('user_id', Auth::user()->user_id)->update([
                        'user_country_id' => $country->country_id
                    ]);
                    $userDefaultFlag = strtolower($countryCode);
                } else {
                    $country = App\Models\Country::getCountries(['country_code'], ['country_id' => Auth::user()->user_country_id])->first();
                    $userDefaultFlag = strtolower($country->country_code);
                }
            }
        }
    }
    return $userDefaultFlag;
}

function convertTimeIntoSystemTime($time)
{
    if (getConfigValueByName('ADMIN_TIME_FORMAT') == Timezone::TWELVE_HOUR) {
        $fromTime = Carbon::createFromTimeString($time);
        return $fromTime->format('g:i A');
    }
    return $time;
}

function physicalProductHeadingRows()
{
    $heading = Product::PHYSICAL_PRODUCT_HEADING;
    $productConfiguration = getConfigValues(['COD_ENABLE', 'PRODUCT_GIFT_WRAP_ENABLE']);
    if (empty($productConfiguration['COD_ENABLE']) && $productConfiguration['COD_ENABLE'] == 0) {
        unset($heading[15]);
    }
    if (empty($productConfiguration['PRODUCT_GIFT_WRAP_ENABLE']) && $productConfiguration['PRODUCT_GIFT_WRAP_ENABLE'] == 0) {
        unset($heading[16]);
    }
    return $heading;
}
function isMobileDevice()
{
    return preg_match(
        "/(android|avantgo|blackberry|bolt|boost|cricket|docomo 
|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
        $_SERVER["HTTP_USER_AGENT"]
    );
}
function getTwoCheckoutConfig()
{
    $package = Package::getRecordBySlug('TwoCheckout');

    if (!empty($package) && count($package) > 0) {
        return $package;
    }
    return '';
}

function getJsCssPath($path, $fileLocation = '')
{
    if (empty($fileLocation)) {
        $fileLocation = public_path() . '/' . $path;
    }
    $version = 100;
    if (file_exists($fileLocation)) {
        $version = filemtime($fileLocation);
    }
    return asset($path) . '?v=' . $version;
}

function getProdSize($size = '', $webp = false)
{
    $imgconfig = imageConfig();
    $mediaType = getConfigValueByName('MEDIA_TYPES');
    $aspectRatio = str_replace(":", "_", Product::PRODUCT_MEDIA_TYPE[$mediaType]);

    if ($size == 'small') {
        $size = $imgconfig["PRODUCT_" . $aspectRatio . "_S"]["WIDTH"] . "-" . $imgconfig["PRODUCT_" . $aspectRatio . "_S"]["HEIGHT"];
    } elseif ($size == 'medium') {
        $size = $imgconfig["PRODUCT_" . $aspectRatio . "_M"]["WIDTH"] . "-" . $imgconfig["PRODUCT_" . $aspectRatio . "_M"]["HEIGHT"];
    } else {
        $size = $imgconfig["PRODUCT_" . $aspectRatio]["WIDTH"] . "-" . $imgconfig["PRODUCT_" . $aspectRatio]["HEIGHT"];
    }
    if ($webp) {
        return $size .'-webp';
    }
    return $size;
}

function notEmpty($variable)
{
    if (!empty($variable) && $variable != null && strtolower($variable) != "null") {
        return $variable;
    }
    return "";
}
function appLang($key)
{
    $strings = Cache::rememberForever('mobile-app-lang', function () {
        return openJSONFile(public_path('/mobile-lang-noti.json'));
    });
    
    if (isset($strings[$key])) {
        return $strings[$key];
    }
    return $key;
}
function getAdminLogos()
{
    $result['lightLogo'] = '';
    $result['darkLogo'] = '';
    $lightLogo = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('adminLogo'), 0);
    $darkLogo = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('adminDarkModeLogo'), 0);
    if (!empty($lightLogo)) {
        $result['lightLogo'] = url('') . '/yokart/image/' . $lightLogo->afile_id . '/thumb?t=' . strtotime($lightLogo->afile_updated_at);
    }
    if (!empty($darkLogo)) {
        $result['darkLogo'] = url('') . '/yokart/image/' . $darkLogo->afile_id . '/thumb?t=' .strtotime($darkLogo->afile_updated_at);
    }
    return $result;
}

function paymentSummaryForApp()
{
    $cartData = Cart::getSummary();
    $priceDetail = [];
    $pickup  = count(Cart::getPickups('name'));
    $priceDetail[] = [
        'key' => 'subtotal',
        'value' => (string) $cartData['subtotal']
    ];
    $priceDetail[] = [
                'key' => 'tax',
                'value' => (string) $cartData['tax']
            ];
    if ($pickup == 0) {
        $priceDetail[] = [
            'key' => 'shipping',
            'value' => (string) $cartData['shipping']
        ];
    }
    if (abs($cartData['coupon']) != 0){
        $priceDetail[] = [
            'key' => 'discount',
            'value' => (string) abs($cartData['coupon'])
        ];
    }
    if ($cartData['rewards'] != 0) {
        $priceDetail[] = [
            'key' => 'reward',
            'value' => (string) $cartData['rewards']
        ];
    }
    if ($cartData['giftPrice'] != 0) {
        $priceDetail[] = [
                'key' => 'gift',
                'value' => (string) $cartData['giftPrice']
            ];
    }
    
    $priceDetail[] = [
                'key' => 'total',
                'value' => (string) $cartData['total']
            ];
    $additionInfo['coupon'] = [
        'name' => $cartData['couponName'],
        'value' =>  (string) abs($cartData['coupon']),
        'error' => $cartData['couponError'],
    ];
    $additionInfo['giftInfo'] = $cartData['giftInfo'];
    $additionInfo['total'] = (string) $cartData['total'];
    return array_merge($additionInfo, ['payment_info' => $priceDetail]);
}
function appContentPages()
{
    return AppPage::where('page_type', AppPage::PAGE_TYPE_CMS)->get()->toArray();
}