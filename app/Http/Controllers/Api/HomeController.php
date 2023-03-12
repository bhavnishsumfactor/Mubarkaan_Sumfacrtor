<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Package;
use App\Models\Currency;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Models\Blog;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\Configuration;
use App\Models\Page;
use App\Models\Order;
use App\Models\UrlRewrite;
use App\Models\Admin\Admin;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use App\Models\AppCollectionToRecord;
use App\Models\AppCollection;
use App\Models\AppExplore;
use App\Models\AppPage;
use App\Models\Reason;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class HomeController extends YokartController
{
    public function index(Request $request)
    {
        $records = AppCollectionToRecord::getAppListing(Page::getPageTypeFlipped()['home']);
        //print_r($records);exit;
        foreach ($records as $record) {
            $fields = 'prod_id, prod_name, REPLACE(pov_display_title, "_","|") as variant_display_title,
            pov_code,' . Product::getPrice() . ',' . \DB::Raw("CONCAT('" . url('') . "', '/yokart/app/product/image/', prod_id, '/', IF(pov_code IS NULL, '0', pov_id), '?t=', UNIX_TIMESTAMP(prod_updated_on)) as prod_image");
            if (count($record['productCollection1Records']) > 0) {
                $prodids = $record['productCollection1Records']->pluck('prod_id')->toArray();
               
                $record['productCollection1Records'] = Product::getListingByidsForApp($fields, $prodids);
            } elseif (count($record['productCollection2Records']) > 0) {
                $prodids = $record['productCollection2Records']->pluck('prod_id')->toArray();
                $record['productCollection2Records'] = Product::getListingByidsForApp($fields, $prodids);
            }
            $record['recent_order'] = null;
            if (Auth::guard('api')->check() && $record['actr_type'] == AppCollection::RECENT_BOUGHT_COLLECTION1) {
                $userId = Auth::guard('api')->user()->user_id;
                $order = Order::getOrdersForApp($userId, 'active', 0, 0, true);
                foreach ($order->products as $product) {
                  
                    $variantNames = "";
                    if (!empty($product->op_product_variants->styles)) {
                        $variantNames = \Arr::flatten($product->op_product_variants->styles);
                        $variantNames = implode(" | ", $variantNames);
                    }
                    $product->op_product_variants = $variantNames;
                }
                $record['recent_order'] = $order;
            }

            if (count($record['bannerSliderCollection1Records']) > 0) {
            }
            if (count($record['bannerSliderCollection2Records']) > 0) {
            }
            if (count($record['bannerSliderCollection3Records']) > 0) {
            }
                
        }
        $response = $records->toArray();
        return apiresponse(config('constants.SUCCESS'), '', ['collections' => $response]);
    }
    public function getSplashScreenData(Request $request)
    {
        $language = Language::getLanguages(['lang_id', 'lang_direction', 'lang_code'], [
            'lang_publish' => config('constants.YES'),
            'lang_default' => config('constants.YES'),
        ])->first();

        $smsPackageCheck = Package::getPublishedPackages('sms');

        $configurations = getConfigValues([
            'FACEBOOK_CLIENT_STATUS',
            'GOOGLE_CLIENT_STATUS',
            'INSTAGRAM_CLIENT_STATUS',
            'APP_DISPLAY_BUY_TOGETHER',
            'PRODUCT_REVIEW_APPROVE_STATUS',
            'ENABLE_EDIT_REVIEW',
            'APP_FONT_FAMILY',
            'APP_LOGO_RATIO',
            'APP_PRIMARY_COLOR',
            'APP_PRIMARY_COLOR_INVERSE',
            'DECIMAL_VALUES',
            'PRODUCT_TYPES',
            'PICK_UP_ENABLE'
        ]);
        
        $response = [];
        $response["language"] = [
            "id" => $language->lang_id,
            "direction" => $language->lang_direction,
            "url" => url("") . "/mobile-lang.json",
            "modified_time" => filemtime(public_path() . "/mobile-lang.json"),
            "lang_code" => $language->lang_code,
        ];
        $response['social_networks'] = [
            'facebook_enabled' => ($configurations['FACEBOOK_CLIENT_STATUS'] == 1) ? "1" : "0",
            'google_enabled' => ($configurations['GOOGLE_CLIENT_STATUS'] == 1) ? "1" : "0",
            'instagram_enabled' => ($configurations['INSTAGRAM_CLIENT_STATUS'] == 1) ? "1" : "0",
        ];
        $response['theme_settings'] = [
            'font_family' => $configurations['APP_FONT_FAMILY'],
            'logo_ratio' => $configurations['APP_LOGO_RATIO'],
            'primary_color' => $configurations['APP_PRIMARY_COLOR'],
            'inverse_color' => $configurations['APP_PRIMARY_COLOR_INVERSE'],
           
        ];
        $response['active_cards'] = isSavedCardsActive();
        $response['sms_enabled'] = ($smsPackageCheck->count() > 0) ? "1" : "0";
        $response['display_buytogether'] = ($configurations['APP_DISPLAY_BUY_TOGETHER'] == 1) ? "1" : "0";
        $response['review_is_autoapproved'] = ($configurations['PRODUCT_REVIEW_APPROVE_STATUS'] == 1) ? "1" : "0";
        $response['review_is_editable'] = ($configurations['ENABLE_EDIT_REVIEW'] == 1) ? "1" : "0";
        $response['decimal_values'] = ($configurations['DECIMAL_VALUES'] == 1) ? true : false;
        $response['pick_up_enable'] = ($configurations['PICK_UP_ENABLE'] == 1) ? true : false;
        $response['product_types'] = (int)$configurations['PRODUCT_TYPES'];
        
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    public function getCountries(Request $request)
    {
        $response = [];
        $response['countries'] = Country::getAll();
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    public function getStates(Request $request, $country_id)
    {
        $response = [];
        $response['states'] = State::getStatesByCountry($country_id);
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    public function getCancellationAndReturnReasons()
    {
        $response = [];
        $response['return_reasons'] = Reason::select('reason_id', 'reason_title')->where(['reason_type' => Reason::RETURN, 'reason_publish' =>  config('constants.YES')])->get();
        $response['cancellation_reasons'] = Reason::select('reason_id', 'reason_title')->where(['reason_type' => Reason::CANCELLATION, 'reason_publish' =>  config('constants.YES')])->get();
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    public function contactUs()
    {
        $bussniessInfo = Configuration::getRecordsByPrefix('BUSINESS_');
        
        $states = '';
        $phoneCode = '';
        if (!empty($bussniessInfo['BUSINESS_STATE'])) {
            $states = State::getRecordById($bussniessInfo['BUSINESS_STATE'])->state_name;
        }
        if (!empty($bussniessInfo['BUSINESS_COUNTRY'])) {
            $country = Country::getRecordById($bussniessInfo['BUSINESS_COUNTRY'])->country_name;
        }
        if (!empty($bussniessInfo['BUSINESS_PHONE_COUNTRY_CODE'])) {
            $phoneCode = '+' . Country::getRecordById($bussniessInfo['BUSINESS_PHONE_COUNTRY_CODE'])->country_phonecode;
        }

      
        $bussniessInfo['BUSINESS_PHONE_COUNTRY_CODE'] = $phoneCode;
        $bussniessInfo['COUNTRY_NAME'] = $country;
        $bussniessInfo['STATE_NAME'] = $states;
        $bussniessInfo['types'] = [
            [
                'key' => 'Buying',
                'value' => $bussniessInfo['BUSINESS_NAME']
            ],
            [
                'key' => 'Shipping',
                'value' => __("LBL_SHIPPING")
            ],
            [
                'key' => 'Account',
                'value' => __("LBL_ACCOUNT")
            ],
            [
                'key' => 'Product I received',
                'value' => __("LBL_PRODUCT_RECEIVED")
            ],
            [
                'key' => 'Returns/exchanges',
                'value' => __("LBL_RETURN_EXCHANGES")
            ],
            [
                'key' => 'Other',
                'value' => __("LBL_OTHER")
            ],
            [
                'key' => 'Technical Issue',
                'value' => __("LBL_TECHNICAL_ISSUE")
            ],
            [
                'key' => 'Feedback',
                'value' => __("LBL_FEEDBACK")
            ],
        ];
  
        return apiresponse(config('constants.SUCCESS'), '', ['info' => $bussniessInfo]);
    }
    public function saveContactEnquiry(Request $request)
    {
      /*  $configurationData = Configuration::getKeyValues(['GOOGLE_RECAPCHA_SECRET_KEY', 'GOOGLE_RECAPCHA_STATUS']);
        if (!empty($secretKey = $configurationData['GOOGLE_RECAPCHA_SECRET_KEY']) && $configurationData['GOOGLE_RECAPCHA_STATUS'] == 1) {
            $result = $this->googleRecaptchaVerification($request->get('recaptcha'), $request->ip(), $secretKey);
            if ($result->success != true) {
                return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
            }
            if ($result->score < 0.3) {
                return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
            }
        }*/
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'title' => 'required',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), '', ['errors' => $validator->errors()]);
        }
        $requestData = $request->all();
        $admin = Admin::select('admin_username', 'admin_email')->where('admin_role_id', null)->first();
        
        /*send email code admin*/
        $data = EmailHelper::getEmailData('contact_page_enquiry');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $data['body']->etpl_subject;
        $data['body'] = str_replace('{name}', $requestData['name'], $data['body']->etpl_body);
        $data['body'] = str_replace('{email}', $requestData['email'], $data['body']);
        $data['body'] = str_replace('{adminName}', $admin->admin_username, $data['body']);
        $data['body'] = str_replace('{title}', $requestData['title'], $data['body']);
        $data['body'] = str_replace('{message}', $requestData['message'], $data['body']);
        $data['body'] = str_replace('{baseurl}', url('/'), $data['body']);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $data['to'] = "sales@fatbit.com"; //$admin->admin_email onlyfordemo
        dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);

        /* send email code buyer */
        $buyerEmaildata = EmailHelper::getEmailData('contact_us_buyer');
        $priority = $buyerEmaildata['body']->etpl_priority;
        $buyerEmaildata['subject'] = $buyerEmaildata['body']->etpl_subject;
        $buyerEmaildata['body'] = str_replace('{name}', $requestData['name'], $buyerEmaildata['body']->etpl_body);
        $buyerEmaildata['body'] = str_replace('{title}', $requestData['title'], $buyerEmaildata['body']);
        $buyerEmaildata['body'] = str_replace('{message}', $requestData['message'], $buyerEmaildata['body']);
        $buyerEmaildata['body'] = str_replace('{baseurl}', url('/'), $buyerEmaildata['body']);
        $buyerEmaildata['body'] = str_replace('{websiteName}', env('APP_NAME'), $buyerEmaildata['body']);
        $buyerEmaildata['body'] = str_replace('{contactUsEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $buyerEmaildata['body']);
        $buyerEmaildata['to'] = $requestData['email'];
        dispatch(new SendNotification(array('email' => $buyerEmaildata)))->onQueue($priority);
        return apiresponse(config('constants.SUCCESS'), __('Your enquiry has been received. Our team will get back to you shortly'));
    }
   
    
    public function contentPages($pageId = "")
    {
        $contentPageEnabled = getConfigValueByName('APP_CONTENT_PAGES_ENABLED');
        
        $page = collect(request()->segments())->last();
        if ($pageId) {
            $page = 'content';
        }
        $response = [];
        $view = "app";
        switch ($page) {
            case 'content':
                $response = AppCollectionToRecord::getContentListingForApp($pageId);
                break;
            case 'privacy':
                if ($contentPageEnabled == 1) {
                    $response = AppCollectionToRecord::getContentListingForApp(2);
                } else {
                    $view = "web";
                    $response = Page::getPageByType('privacy');
                }
                break;
            case 'terms':
                if ($contentPageEnabled == 1) {
                    $response = AppCollectionToRecord::getContentListingForApp(3);
                } else {
                    $view = "web";
                    $response = Page::getPageByType('terms');
                }
                break;
        }
        if ($view != "web") {
            foreach ($response as $record) {
                $record->ac_type = AppCollection::TAGS_TYPES[$record->ac_type];
            }
        }
        $records['view'] = $view;
        $records['records'] = $response;
        return apiresponse(config('constants.SUCCESS'), '', $records);
    }
    public function currencyList()
    {
        $records = getCurrencies()->toArray();
        return apiresponse(config('constants.SUCCESS'), '', ['currencies' => $records]);
    }
    public function cmsPages()
    {
        
        $contentPageEnabled = getConfigValueByName('APP_CONTENT_PAGES_ENABLED');
        $extraPages = [];       
        $quickLinksPages = [];    
        $explorePages = AppExplore::getAllPages()->toArray();      
        if (!empty($explorePages)) {
            foreach ($explorePages as $explorePage) {
                $explorePage['app_slug'] = $explorePage['page_slug'];
                $explorePage['view'] = 'app';
                $explorePage['content'] = [];
                $explorePage['page_url'] = '';

                switch ($explorePage['page_type']) {
                    case AppPage::PAGE_TYPE_STATIC:
                        if ($explorePage['page_slug'] == 'contact-us') {
                            $explorePage['app_slug'] = 'contact-us';
                        } else if ($explorePage['page_slug'] == 'blogs') {
                        $explorePage['app_slug'] = 'blogs';
                        } else if ($explorePage['page_slug'] == 'faqs') {
                            $explorePage['app_slug'] = 'faqs';
                        }
                    break;
                case AppPage::PAGE_TYPE_LINK:
                    if ($contentPageEnabled == 1) {
                    
                        $explorePage['view'] = 'app';
                        $explorePage['content'] = AppCollectionToRecord::getContentListingForApp($explorePage['page_id']);
                    } else {
                        $explorePage['view'] = 'web';
                        if ($explorePage['page_slug'] == 'terms' || $explorePage['page_slug'] == 'privacy') {
                            $explorePage['page_url'] = url('/') . '/' .$explorePage['page_slug'];
                        }                       
                    }
                    break;
                }
                if ($explorePage['type'] == AppExplore::EXPLORE_PAGE_TYPE_EXTRA) {
                    $extraPages[] = $explorePage;
                } else if ($explorePage['type'] == AppExplore::EXPLORE_PAGE_TYPE_QUICK_LINKS) {
                    $quickLinksPages[] = $explorePage;
                }
            }
        } 
        $langData = openJSONFile(base_path('public/mobile-lang.json'));
       $response[0]['title'] = $langData['LBL_EXTRA'] ?? 'Extra';
        $response[0]['type'] = AppExplore::EXPLORE_PAGE_TYPE_EXTRA;
        $response[0]['data'] = $extraPages;
        $response[1]['title'] = $langData['LBL_QUICK_LINKS'] ?? 'Quick Links';
        $response[1]['type'] = AppExplore::EXPLORE_PAGE_TYPE_QUICK_LINKS;
        $response[1]['data'] = $quickLinksPages;
        return apiresponse(config('constants.SUCCESS'), '', ['pages' => $response]);
    }
    public function blogs($pageId = 1)
    {
        $blogs = BlogPost::getBlogsForApp($pageId);
        foreach ($blogs as $blog) {
            $blog->created_at = getConvertedDateTime($blog->post_created_at, false);
            $blog->updated_at = getConvertedDateTime($blog->post_updated_at, false);
            unset($blog->post_created_at);
            unset($blog->post_updated_at);
        }
        return apiresponse(config('constants.SUCCESS'), '', ['blogs' => $blogs]);
    }
    public function showUrlRewrittenPages($rewrite_url = '')
    {
        
        $rewriteData = UrlRewrite::getByCustomUrl($rewrite_url);
        $response = [];
        $status = config('constants.ERROR');
        if (!empty($rewriteData->urlrewrite_type)) {
            switch ($rewriteData->urlrewrite_type) {
                case 1:
                    $response = ['type' => 'product', 'id' => $rewriteData->urlrewrite_record_id];
                    $status = config('constants.SUCCESS');
                    break;
                case 3:
                    $response = ['type' => 'category', 'id' => $rewriteData->urlrewrite_record_id];
                    $status = config('constants.SUCCESS');
                    break;
                case 4:
                    $response = ['type' => 'blog', 'id' => $rewriteData->urlrewrite_record_id];
                    $status = config('constants.SUCCESS');

                    break;
                case 5:
                      $response = ['type' => 'brand', 'id' => $rewriteData->urlrewrite_record_id];
                      $status = config('constants.SUCCESS');
                    break;
                default:
                    break;
            }
        }
        return apiresponse($status, '', $response);
    }
}
