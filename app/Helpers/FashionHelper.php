<?php
namespace App\Helpers;

use App\Helpers\ThemeHelper;
use App\Helpers\SocialHelper;
use App\Models\AttachedFile;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductToCategory;
use App\Models\Collection;
use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\Slide;
use App\Models\CollectionMeta;
use App\Models\BlogPost;
use App\Models\Testimonial;
use App\Models\CollectionLabel;
use App\Models\InstagramFeedToken;
use App\Models\Faq;
use DB;
use Arr;

class FashionHelper extends ThemeHelper
{
    public const WIDGETS = [
        6 => 'bannerSlider',
        18 => 'blogLayout1',
        21 => 'blogLayout2',
        36 => 'blogCollection3',
        1 => 'brands',
        3 => 'categoryCollectionLayout1',
        14 => 'categoryCollectionLayout2',
        32 => 'categoryCollection3',
        33 => 'categoryCollection4',
        42 => 'cmsCollection1',
        19 => 'contactForm',
        101 => 'contactPage',
        38 => 'faqCollection1',
        23 => 'footer1',
        7 => 'footer2',
        16 => 'headerBody',
        17 => 'header',
        24 => 'highlightedText',
        30 => 'hrTag',
        27 => 'imageGallery',
        12 => 'instagramLayout1',
        13 => 'instagramLayout2',
        35 => 'instagramCollection3',
        11 => 'logo',
        31 => 'customMap',
        5 => 'navigationLayout1',
        10 => 'newsletterFullwidth',
        39 => 'newsletterCollection2',
        2 => 'productCollectionLayout1',
        4 => 'productCollectionLayout2',
        9 => 'promotionalBannerLayout1',
        37 => 'promotionalBannerCollection2',
        40 => 'promotionalBannerCollection3',
        20 => 'testimonialLayout1',
        34 => 'testimonialCollection2',
        28 => 'textWithTwoImagesOnLeft',
        29 => 'textWithTwoImagesOnRight',
        26 => 'titleWithBackgroundImage',
        8 => 'trustBanners',
        41 => 'trustBannersCollection2',
        25 => 'twoColumnTextCta',
    ];

    public static function get($excludeIds = [])
    {
        $generalWidgetHtml = ThemeHelper::get();
        $themeWidgetNames = self::WIDGETS;
        if (!empty($excludeIds) && count($excludeIds) > 0) {
            $themeWidgetNames = Arr::except($themeWidgetNames, $excludeIds);
        }
        $themeWidgetHtml = array_map(function ($widget) {
            return self::$widget();
        }, $themeWidgetNames);
        $themeWidgetHtml = implode(' ', $themeWidgetHtml);
        return $generalWidgetHtml . $themeWidgetHtml;
    }

    public static function getWidgetNames()
    {
        return self::WIDGETS;
    }

    public static function getWidgetsFlipped()
    {
        $generalWidgets = ThemeHelper::getWidgetNames();
        $themeWidgets = self::WIDGETS;
        return array_flip($generalWidgets + $themeWidgets);
    }

    public static function brands()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_BRANDS");
        $params = ['fromEditor' => true, 'innerHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function brandsContent($pageid, $cid, $fromEditor = false, $innerHtml = false)
    {
         $brandIds = Collection::getRecordIdsByCid($pageid, $cid);
        $brands = Brand::getBrandsById($brandIds);
        return view('themes.' . config('theme') . '.widgets.brands', compact('brands', 'cid', 'fromEditor', 'innerHtml'))->render();
    }

    public static function productCollectionLayout1()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_PRODUCT_LAYOUT1");
        $params = ['productsinternalHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function productCollectionLayout1Content($pageid, $cid, $partials = '', $escapeCurrency = false, $productsinternalHtml = false)
    {
        $productIds = Collection::getRecordIdsByCid($pageid, $cid);
        $products = [];
        if (!empty($productIds) && count($productIds) > 0) {
            $products = Product::getDataByIds($productIds, [], false, false);
        }
        $currentCurrency = currencyFromSession();
        if ($escapeCurrency  && $currentCurrency['currency_code'] == 'USD') {
            $escapeCurrency = '\\';
        } else {
            $escapeCurrency = '';
        }
        return view('themes.' . config('theme') . '.widgets' . $partials . '.productCollectionLayout1', compact('products', 'cid', 'escapeCurrency', 'productsinternalHtml'))->render();
    }

    public static function productCollectionLayout2()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_PRODUCT_LAYOUT2");
        $params = ['productsinternalHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function productCollectionLayout2Content($pageid, $cid, $partials = '', $escapeCurrency = false, $productsinternalHtml = false)
    {
        $productIds = Collection::getRecordIdsByCid($pageid, $cid);
        $products = [];
        if (!empty($productIds) && count($productIds) > 0) {
            $products = Product::getDataByIds($productIds, [], false, false);
        }
        $currentCurrency = currencyFromSession();
        if ($escapeCurrency  && $currentCurrency['currency_code'] == 'USD') {
            $escapeCurrency = '\\';
        } else {
            $escapeCurrency = '';
        }
        return view('themes.' . config('theme') . '.widgets' . $partials . '.productCollectionLayout2', compact('products', 'cid', 'escapeCurrency', 'productsinternalHtml'))->render();
    }
    
    public static function refreshProductLayouts($pageid, $cid, $layout)
    {
        $productIds = Collection::getRecordIdsByCid($pageid, $cid);
        $products = [];
        if (!empty($productIds) && count($productIds) > 0) {
            $products = Product::getDataByIds($productIds, [], false, false);
        }
        $currentCurrency = currencyFromSession();
        if ($currentCurrency['currency_code'] == 'USD') {
            $escapeCurrency = '\\';
        } else {
            $escapeCurrency = '';
        }
        return view('themes.' . config('theme') . '.shortcodes.' . $layout, compact('products', 'cid', 'escapeCurrency'))->render();
    }
    
    public static function categoryCollectionLayout1()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_CATEGORY_LAYOUT1");
        $params = ['productsinternalHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function categoryCollectionLayout1Content($pageid, $cid, $escapeCurrency = false, $productsinternalHtml = false)
    {
        $collections = Collection::getCollectionsInHierarchy($pageid, $cid, null, null, false);
      
        $currentCurrency = currencyFromSession();
        if ($escapeCurrency  && $currentCurrency['currency_code'] == 'USD') {
            $escapeCurrency = '\\';
        } else {
            $escapeCurrency = '';
        }
        return view('themes.' . config('theme') . '.widgets.categoryCollectionLayout1', compact('collections', 'cid', 'escapeCurrency', 'productsinternalHtml'))->render();
    }

    public static function categoryCollectionLayout1Categories($pageid, $cid)
    {
        $collections = Collection::getCollectionsInHierarchy($pageid, $cid, null, null, false);
        return view('themes.' . config('theme') . '.widgets.partials.getCategoriesTabbing', compact('collections'))->render();
    }

    public static function refreshCategoryLayout($pageid, $cid)
    {
      
            $collections = Collection::getCollectionsInHierarchy($pageid, $cid, null, null, false);
            $currentCurrency = currencyFromSession();
            if ($currentCurrency['currency_code'] == 'USD') {
                $escapeCurrency = '\\';
            } else {
                $escapeCurrency = '';
            }
            return view('themes.' . config('theme') . '.shortcodes.categoryCollectionLayout1', compact('collections', 'cid', 'escapeCurrency'))->render();
    }

    public static function navigationLayout1()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_NAVMENU");
        return self::getBlock($layout, $title);
    }

    public static function navigationLayout1Content($pageid, $cid)
    {
        $layout = 'navigationLayout1';
        $welcome_text = "";
        $widgets = self::getWidgetsFlipped();

        $collections = self::navigationMenuCollections($pageid, $cid, $layout);
        
        $collection = Collection::getRecord($pageid, $cid, $widgets[$layout]);
        if (!empty($collection->collection_id)) {
            $welcome_text = CollectionMeta::getValue($collection->collection_id, 'WELCOME_TEXT');
        }

        return view('themes.' . config('theme') . '.widgets.headerBody', compact('collections', 'cid', 'welcome_text'))->render();
    }

    public static function bannerSlider()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_BANNER_SLIDER");
        return self::getBlock($layout, $title);
    }

   

    public static function bannerSliderContent($pageid, $cid, $layout, $fromEditor = false)
    {
        $collections = Collection::getCollectionIds($pageid, $cid, $layout);
        $collection_id = $collections[0];
        $collections = Slide::getSlides($collection_id);
        if (!empty($collections)) {
            foreach ($collections as $key => $slide) {
                $bannerSlide = AttachedFile::getAttachedFile(AttachedFile::SECTIONS['bannerSlider'], $collection_id, $slide->slide_id, AttachedFile::DEVICE_TYPE['desktop']);
                $collections[$key]->desktop = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/thumb?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
            }
        }
        return view('themes.' . config('theme') . '.widgets.bannerSlider', compact('collections', 'cid', 'fromEditor'))->render();
    }
    public static function footer2()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_FOOTER2");
        return self::getBlock($layout, $title);
    }

    public static function footer1()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_FOOTER1");
        return self::getBlock($layout, $title);
    }

    public static function trustBanners()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_TRUST_BANNERS");
        return self::getBlock($layout, $title);
    }

    public static function trustBannersCollection2()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_TRUSTBANNERSCOLLECTION2");
        return self::getBlock($layout, $title);
    }

    public static function cmsCollection1()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_CMSCOLLECTION1");
        return self::getBlock($layout, $title);
    }

    public static function promotionalBannerLayout1()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_PROMO_BANNER1");
        return self::getBlock($layout, $title);
    }

    public static function promotionalBannerLayout1Content($pageid, $cid, $layout)
    {
        $collections = [];
        $collection_id = Collection::generateCollectionId($pageid, $cid, $layout);

        $collectionMetas = CollectionMeta::getValue($collection_id, ['PROMOTIONAL_BANNER_TEXT1', 'PROMOTIONAL_BANNER_TEXT2', 'PROMOTIONAL_BANNER_CTA_LABEL', 'PROMOTIONAL_BANNER_CTA_LINK', 'PROMOTIONAL_BANNER_CTA_TARGET']);

        $collections['text1'] = $collectionMetas['PROMOTIONAL_BANNER_TEXT1'];
        $collections['text2'] = $collectionMetas['PROMOTIONAL_BANNER_TEXT2'];
        $collections['cta_label'] = $collectionMetas['PROMOTIONAL_BANNER_CTA_LABEL'];
        $collections['cta_link'] = $collectionMetas['PROMOTIONAL_BANNER_CTA_LINK'];
        $collections['cta_target'] = $collectionMetas['PROMOTIONAL_BANNER_CTA_TARGET'];
        $collections['banner'] = AttachedFile::getFileUrl('promotionalBannerLayout1', $collection_id, 0, 'thumb');
        if (empty($collections['banner'])) {
            $collections['banner'] = noImage("2_1/2000x1000.png");
        }
        return view('themes.' . config('theme') . '.widgets.promotionalBannerLayout1', compact('collections', 'cid'))->render();
    }

    public static function promotionalBannerCollection3()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_PROMOTIONALBANNERCOLLECTION3");
        return self::getBlock($layout, $title);
    }

    public static function promotionalBannerCollection3Content($pageid, $cid, $layout)
    {
        $collections = [];
        $collection_id = Collection::generateCollectionId($pageid, $cid, $layout);

        $collectionMetas = CollectionMeta::getValue($collection_id, ['PROMOTIONAL_BANNER_TARGET', 'PROMOTIONAL_BANNER_LINK']);
        $collections['target'] = $collectionMetas['PROMOTIONAL_BANNER_TARGET'];
        $collections['link'] = $collectionMetas['PROMOTIONAL_BANNER_LINK'];
        $collections['cropped'] = AttachedFile::getFileUrl('promotionalBannerCollection3', $collection_id, 0, '2000-500');
        $collections['webp'] = AttachedFile::getFileUrl('promotionalBannerCollection3', $collection_id, 0, '2000-500-webp');
      
        return view('themes.' . config('theme') . '.widgets.promotionalBannerCollection3', compact('collections', 'cid'))->render();
    }

    public static function newsletterFullwidth()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_NEWSLETTER_FULLWIDTH");
        return self::getBlock($layout, $title);
    }

    public static function newsletterFullwidthContent($pageid, $cid, $layout)
    {
        $collections = [];
        $collection_id = Collection::generateCollectionId($pageid, $cid, $layout);

        $collectionMetas = CollectionMeta::getValue($collection_id, ['NEWSLETTER_BANNER_TEXT1', 'NEWSLETTER_BANNER_TEXT2']);
        $collections['text1'] =   $collectionMetas['NEWSLETTER_BANNER_TEXT1'];
        $collections['text2'] = $collectionMetas['NEWSLETTER_BANNER_TEXT2'];
        $collections['banner'] = AttachedFile::getFileUrl('newsletterFullwidth', $collection_id, 0, 'thumb');
        if (empty($collections['banner'])) {
            $collections['banner'] = noImage("4_1/2000x500.png");
        }
        return view('themes.' . config('theme') . '.widgets.newsletterFullwidth', compact('collections', 'cid'))->render();
    }

    public static function logo()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_LOGO");
        $logo = AttachedFile::getFileUrl('frontendLogo', 0, 0, 'thumb');
        if (empty($logo)) {
            $logo = noImage("4_1/160x40.png");
        }
        $configurations = getConfigValues(['FRONTEND_LOGO_RATIO']);
        $params = ['logo' => $logo, 'ratio' => $configurations['FRONTEND_LOGO_RATIO']];
        return self::getBlock($layout, $title, $params);
    }

    public static function instagramLayout1()
    {
        $iftoken = InstagramFeedToken::getValidTokenData();
        $collections = [];
        if (!empty($iftoken->iftoken_access_code)) {
            $collections = SocialHelper::getInstagramData($iftoken->iftoken_user_id, $iftoken->iftoken_access_code, $iftoken->iftoken_username, 4);
        }
        
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_INSTAGRAM_LAYOUT1");
        $params = [
            'collections' => $collections,
            'innerHtml' => false
        ];
        return self::getBlock($layout, $title, $params);
    }

    public static function instagramLayout2()
    {
        $iftoken = InstagramFeedToken::getValidTokenData();
        $collections = [];
        if (!empty($iftoken->iftoken_access_code)) {
            $collections = SocialHelper::getInstagramData($iftoken->iftoken_user_id, $iftoken->iftoken_access_code, $iftoken->iftoken_username, 16);
        }

        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_INSTAGRAM_LAYOUT2");
        $params = [
            'collections' => $collections,
            'innerHtml' => false
        ];
        return self::getBlock($layout, $title, $params);
    }
 
    public static function instagramCollection3()
    {
        $iftoken = InstagramFeedToken::getValidTokenData();
        $collections = [];
        if (!empty($iftoken->iftoken_access_code)) {
            $collections = SocialHelper::getInstagramData($iftoken->iftoken_user_id, $iftoken->iftoken_access_code, $iftoken->iftoken_username, 6);
        }

        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_INSTAGRAMCOLLECTION3");
        $params = [
            'collections' => $collections,
            'innerHtml' => false
        ];
        return self::getBlock($layout, $title, $params);
    }

    public static function categoryCollectionLayout2()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_CATEGORY_LAYOUT2");
        return self::getBlock($layout, $title);
    }

    public static function categoryCollectionLayout2Content($pageid, $cid, $layout)
    {
        $collections = ProductCategory::getCategoryCollections($pageid, $cid, AttachedFile::SECTIONS['categoryCollectionLayout2']);
      
        return view('themes.' . config('theme') . '.widgets.categoryCollectionLayout2', compact('collections', 'cid'))->render();
    }

    public static function headerBody()
    {
        $logo = AttachedFile::getFileUrl('frontendLogo', 0, 0, 'thumb');
        if (empty($logo)) {
            $logo = noImage("4_1/160x40.png");
        }
        $configurations = getConfigValues(['FRONTEND_LOGO_RATIO']);
        
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_HEADER");
        $params = ['logo' => $logo, 'ratio' => $configurations['FRONTEND_LOGO_RATIO']];
        return self::getBlock($layout, $title, $params);
    }

    public static function header()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_HEADER_LAYOUT");
        return self::getBlock($layout, $title);
    }


    public static function blogLayout1()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_BLOG_LAYOUT1");
        $params = ['innerHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function blogLayout1Content($pageid, $cid, $layout, $innerHtml = false)
    {
        $postIds = Collection::getRecordIdsByCid($pageid, $cid);
        $collections = BlogPost::blogCollectionRecords($postIds);
        return view('themes.' . config('theme') . '.widgets.blogLayout1', compact('collections', 'cid', 'innerHtml'))->render();
    }
    
    public static function blogLayout2()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_BLOG_LAYOUT2");
        $params = ['innerHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function blogLayout2Content($pageid, $cid, $layout, $innerHtml = false)
    {
        $postIds = Collection::getRecordIdsByCid($pageid, $cid);
        $collections = BlogPost::blogCollectionRecords($postIds);
        return view('themes.' . config('theme') . '.widgets.blogLayout2', compact('collections', 'cid', 'innerHtml'))->render(); 
    }

    public static function testimonialLayout1()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_TESTIMONIAL_LAYOUT1");
        $params = ['fromEditor' => true, 'innerHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function testimonialLayout1Content($pageid, $cid, $fromEditor = false, $innerHtml = false)
    {
        $testimonialIds = Collection::getRecordIdsByCid($pageid, $cid);
        $query = Testimonial::select(
            'testimonial_id',
            'testimonial_user_name',
            'testimonial_designation',
            'testimonial_title',
            'testimonial_description',
            'testimonial_updated_at',
            DB::raw("DATE_FORMAT(testimonial_created_at, '%d %b, %Y') as testimonial_created_at"),
            'af.afile_id'
        )
        ->leftJoin('attached_files AS af', function ($join) {
            $join->on('testimonial_id', '=', 'af.afile_record_id');
            $join->where('af.afile_type', AttachedFile::getConstantVal('testimonialMedia'));
        })
        ->where('testimonial_publish', config("constants.YES"))->whereIn('testimonial_id', $testimonialIds);
        if (!empty($testimonialIds) && count($testimonialIds) > 0) {
            $orderByIds = implode(',', $testimonialIds);
            $query->orderByRaw("FIELD(testimonial_id, $orderByIds)");
        }
        $collections = $query->get();
        return view('themes.' . config('theme') . '.widgets.testimonialLayout1', compact('collections', 'cid', 'fromEditor', 'innerHtml'))->render();
    }

    public static function contactForm()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_CONTACT_FORM");
        return self::getBlock($layout, $title);
    }

    public static function contactPage()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_CONTACT_PAGE");
        return self::getBlock($layout, $title);
    }

    /*helper functions to replace collections with dynamic data*/
    public static function updateShortcodes($pageHtml, $pageId)
    {
        
        //Newsletter
        $pageHtml = str_replace('newsletter-action=""', ' action="' . route("newsletterPost") . '"', $pageHtml);

        $pageHtml = self::removeGrapesjsTags($pageHtml);
        
        $pageHtml = FashionHelper::loadBannerSlider($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadBrandsSlider($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadInstagramLayout1Feed($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadInstagramLayout2Feed($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadInstagramCollection3Feed($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadtestimonialLayout1($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadTestimonialCollection2($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadProductLayout1($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadProductLayout2($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadCategoryLayout1($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadBlogLayout1($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadBlogLayout2($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadBlogCollection3($pageHtml, $pageId);
        $pageHtml = FashionHelper::loadFaqCollection1($pageHtml, $pageId);
        return $pageHtml;
    }

    public static function removeGrapesjsTags($pageHtml)
    {
        $tagsToRemove = [
            ' data-gjs-type="default"',
            ' data-gjs-type="image"',
            ' data-gjs-type="link"',
            ' data-gjs-type="text"',
            ' data-gjs-type="form"',
            ' gjs-selected',
            ' data-highlightable="1"',
            ' draggable="true"',
            'content-min-height'
        ];
        foreach ($tagsToRemove as $tag) {
            $pageHtml = str_replace($tag, '', $pageHtml);
        }
        return $pageHtml;
    }

    public static function loadBrandsSlider($pageHtml, $pageId)
    {
       
            $tempHtml = $pageHtml;
            preg_match_all('/<brands(.*?)<\/brands>/s', $tempHtml, $brandsInstances);
            if (!empty($brandsInstances[0])) {
                foreach ($brandsInstances[0] as $key => $brand) {
                    $Html = explode('compid="', $brand);
                    $cid = explode('"', $Html[1])[0];
                    $Html =  \Cache::rememberForever('home-brand-section-' . $pageId . '-' . $cid, function () use ($cid, $pageId) {
                        return FashionHelper::brandsContent($pageId, $cid, false, true);
                    });
                    $pageHtml = preg_replace('/<brands compid=\"' . $cid . '\"(.*?)<\/brands>/s', $Html, $pageHtml, 1);
                }
            }
            return $pageHtml;
    }

    
    public static function loadBannerSlider($pageHtml, $pageId)
    {

            
        $tempHtml = $pageHtml;
        preg_match_all('/class="yk-bannerSlider(.*?)<\/section>/s', $tempHtml, $bannerslider);
        if (!empty($bannerslider[0])) {
            foreach ($bannerslider[0] as $key => $banner) {
                
                $bannerHtml = explode('compid="', $banner);
                $cid = explode('"', $bannerHtml[1])[0];
                $bannerSliderHtml = \Cache::rememberForever('home-banner-section-' . $pageId . '-' . $cid, function () use ($cid, $pageId) {
                    $data = [];
                    $widgets = FashionHelper::getWidgetsFlipped();
                    $collection_id = Collection::generateCollectionId($pageId, $cid, $widgets['bannerSlider']);
                    $data['slider_duration'] = CollectionMeta::getValue($collection_id, 'BANNER_SLIDER_DURATION');
                    $data['slides'] = Slide::getSlides($collection_id);
                    return view('themes.' . config('theme') . '.widgets.partials.bannerSlider', compact('data', 'cid'))->render();
                });
                $pageHtml = preg_replace('/<bannerslider compid=\"' . $cid . '\"(.*?)<\/bannerslider>/s', $bannerSliderHtml, $pageHtml, 1);
            }
        }
        return $pageHtml;
    }
    
    public static function loadNavigationLayout1($pageHtml, $pageId)
    {
        $tempHtml = $pageHtml;
        preg_match_all('/<navmenu-shortcode(.*?)<\/navmenu-shortcode>/s', $tempHtml, $instances);
        if (!empty($instances[0]) && !empty($instances[0][0])) {
            $navigation = $instances[0][0];
            $Html = explode('compid="', $navigation);
            $cid = explode('"', $Html[1])[0];
            $navmenuHtml = \Cache::rememberForever('header-section', function () use ($pageId, $cid) {
                $collections = self::navigationMenuCollections($pageId, $cid, 'navigationLayout1');
                // dd($collections);
                return view('themes.' . config('theme') . '.shortcodes.navmenu', compact('collections'))->render();
            });
            $pageHtml = preg_replace('/<navmenu-shortcode(.*?)<\/navmenu-shortcode>/s', $navmenuHtml, $pageHtml, 1);
        }
        return $pageHtml;
    }

    private static function navigationMenuCollections($pageid, $cid, $layout)
    {
        $widgets = self::getWidgetsFlipped();
        $collections = Collection::getCollectionIds($pageid, $cid, $widgets[$layout]);
        if (!empty($collections[0])) {
            $collection_id = $collections[0];
            $collections = NavigationMenu::getMenusByCollectionId($collection_id);
            if (!empty($collections)) {
                foreach ($collections as $key => $collection) {
                    if ($collection->navmenu_type == '1') {
                        $productCategories = ProductCategory::getNavCategories($collection->navmenu_record_id);
                        $collections[$key]['children'] = $productCategories;
                    }
                }
            }
        }
        
        return $collections;
    }

    public static function loadFaqCollection1($pageHtml, $pageId)
    {
       
        $tempHtml = $pageHtml;
        preg_match_all('/yk-faqCollection1(.*?)<\/section>/s', $tempHtml, $instances);
        if (!empty($instances[0])) {
            foreach ($instances[0] as $key => $blog) {
                $Html = explode('compid="', $blog);
                $cid = explode('"', $Html[1])[0];
                $Html = \Cache::rememberForever('faq-section1-' . $pageId . '-' . $cid, function () use ($cid, $pageId) {
                    return FashionHelper::faqCollection1Content($pageId, $cid, 'faqCollection1', true);
                });
                $pageHtml = preg_replace('/<faqcollection1(.*?)<\/faqcollection1>/s', $Html, $pageHtml, 1);
            }
        }
        return $pageHtml;
    }
    
    public static function loadBlogLayout2($pageHtml, $pageId)
    {
      
        $tempHtml = $pageHtml;
        preg_match_all('/yk-blogLayout2(.*?)<\/section>/s', $tempHtml, $instances);
        if (!empty($instances[0])) {
            foreach ($instances[0] as $key => $blog) {
                $Html = explode('compid="', $blog);
                $cid = explode('"', $Html[1])[0];
                $Html = \Cache::rememberForever('blog-section2-' . $pageId . '-' . $cid, function () use ($cid, $pageId) {
                    return FashionHelper::blogLayout2Content($pageId, $cid, 'blogLayout2', true);
                });
                $pageHtml = preg_replace('/<blogcollectionlayout2(.*?)<\/blogcollectionlayout2>/s', $Html, $pageHtml, 1);
            }
        }
        return $pageHtml;
    }

    public static function loadBlogCollection3($pageHtml, $pageId)
    {
        $tempHtml = $pageHtml;
        preg_match_all('/yk-blogCollection3(.*?)<\/section>/s', $tempHtml, $instances);
        if (!empty($instances[0])) {
            foreach ($instances[0] as $key => $blog) {
                $Html = explode('compid="', $blog);
                $cid = explode('"', $Html[1])[0];
                $Html = \Cache::rememberForever('blog-section3-' . $pageId . '-' . $cid, function () use ($cid, $pageId) {
                    return FashionHelper::blogCollection3Content($pageId, $cid, 'blogCollection3', true, true);
                });
                $pageHtml = preg_replace('/<blogcollection3(.*?)<\/blogcollection3>/s', $Html, $pageHtml, 1);
            }
        }
        return $pageHtml;
    }

    public static function loadBlogLayout1($pageHtml, $pageId)
    {
        
            $tempHtml = $pageHtml;
            preg_match_all('/yk-blogLayout1(.*?)<\/section>/s', $tempHtml, $instances);
            if (!empty($instances[0])) {
                foreach ($instances[0] as $key => $blog) {
                    $Html = explode('compid="', $blog);
                    $cid = explode('"', $Html[1])[0];
                    $Html =  \Cache::rememberForever('blog-section1-' . $pageId . '-' . $cid, function () use ($cid, $pageId) {
                        return FashionHelper::blogLayout1Content($pageId, $cid, 'blogLayout1', true);
                    });
                    $pageHtml = preg_replace('/<blogcollectionlayout1(.*?)<\/blogcollectionlayout1>/s', $Html, $pageHtml, 1);
                }
            }
            return $pageHtml;
    }

    public static function loadtestimonialLayout1($pageHtml, $pageId)
    {

        
        $tempHtml = $pageHtml;
        preg_match_all('/yk-testimonialLayout1(.*?)<\/section>/s', $tempHtml, $testimonialLayout1Instances);
        if (!empty($testimonialLayout1Instances[0])) {
            foreach ($testimonialLayout1Instances[0] as $key => $testimonial) {
                $Html = explode('compid="', $testimonial);
                $cid = explode('"', $Html[1])[0];
                $Html = \Cache::rememberForever('testimonial-section1-' . $pageId . '-' . $cid, function () use ($cid, $pageId) {
                    return FashionHelper::testimonialLayout1Content($pageId, $cid, false, true);
                });
                $pageHtml = preg_replace('/<testimoniallayout1 compid=\"' . $cid . '\"(.*?)<\/testimoniallayout1>/s', $Html, $pageHtml, 1);
            }
        }
        return $pageHtml;
       
    }

    public static function loadTestimonialCollection2($pageHtml, $pageId)
    {
        
            $tempHtml = $pageHtml;
            preg_match_all('/yk-testimonialCollection2(.*?)<\/section>/s', $tempHtml, $instances);
            if (!empty($instances[0])) {
                foreach ($instances[0] as $key => $record) {
                    $Html = explode('compid="', $record);
                    $cid = explode('"', $Html[1])[0];
                    $Html = \Cache::rememberForever('testimonial-section2-' . $pageId . '-' . $cid, function () use ($cid, $pageId) {
                         return FashionHelper::testimonialCollection2Content($pageId, $cid, false, true);
                    });
                    $pageHtml = preg_replace('/<testimonialcollection2 compid=\"' . $cid . '\"(.*?)<\/testimonialcollection2>/s', $Html, $pageHtml, 1);
                }
            }
            return $pageHtml;
        
    }

    public static function loadCategoryLayout1($pageHtml, $pageId)
    {
            
        $tempHtml = $pageHtml;
        preg_match_all('/yk-categoryCollectionLayout1(.*?)<\/section>/s', $tempHtml, $categoryLayout1Instances, PREG_PATTERN_ORDER);
        if (!empty($categoryLayout1Instances[0])) {
            foreach ($categoryLayout1Instances[0] as $key => $product) {
                $Html = explode('compid="', $product);
                $cid = explode('"', $Html[1])[0];
                $html = \Cache::rememberForever('category-section1-' . $pageId . '-' . $cid, function () use ($pageId, $cid) {
                    return FashionHelper::refreshCategoryLayout($pageId, $cid);
                });
                $pageHtml = preg_replace('/<categorycollectionlayout1\s(.+?)compid=\"' . $cid . '\"(.*?)<\/categorycollectionlayout1>/s', $html, $pageHtml, 1);
            }
        }
        return $pageHtml;
    }


    public static function loadProductLayout1($pageHtml, $pageId)
    {
        $tempHtml = $pageHtml;
        preg_match_all('/yk-productCollectionLayout1(.*?)<\/section>/s', $tempHtml, $productLayout1Instances, PREG_PATTERN_ORDER);
        if (!empty($productLayout1Instances[0])) {
            foreach ($productLayout1Instances[0] as $key => $product) {
                $html = explode('compid="', $product);
                $cid = explode('"', $html[1])[0];
                $html = FashionHelper::refreshProductLayouts($pageId, $cid, 'productCollectionLayout1');
                $pageHtml = preg_replace('/<productcollectionlayout1\s(.+?)compid=\"' . $cid . '\"(.*?)<\/productcollectionlayout1>/s', $html, $pageHtml, 1);
            }
        }
        return $pageHtml;
    }

    public static function loadProductLayout2($pageHtml, $pageId)
    {
        $tempHtml = $pageHtml;
        preg_match_all('/yk-productCollectionLayout2(.*?)<\/section>/s', $tempHtml, $productLayout2Instances, PREG_PATTERN_ORDER);
        if (!empty($productLayout2Instances[0])) {
            foreach ($productLayout2Instances[0] as $key => $product) {
                $html = explode('compid="', $product);
                $cid = explode('"', $html[1])[0];
                $html = FashionHelper::refreshProductLayouts($pageId, $cid, 'productCollectionLayout2');
                $pageHtml = preg_replace('/<productcollectionlayout2\s(.+?)compid=\"' . $cid . '\"(.*?)<\/productcollectionlayout2>/s', $html, $pageHtml, 1);
            }
        }
        return $pageHtml;
    }

    public static function loadInstagramLayout1Feed($pageHtml, $pageId)
    {
       
        $tempHtml = $pageHtml;
        $innerHtml = true;
        preg_match_all('/yk-instagramLayout1(.*?)<\/section>/s', $tempHtml, $instagramLayout1Instances);
        if (!empty($instagramLayout1Instances[0])) {
            foreach ($instagramLayout1Instances[0] as $key => $instagram) {
                $Html =  \Cache::rememberForever('insta-section1-' . $pageId . '-' . $key, function () use ($innerHtml, $pageId) {
                    $iftoken = InstagramFeedToken::getValidTokenData();
                    $collections = [];
                    if (!empty($iftoken->iftoken_access_code)) {
                        $collections = SocialHelper::getInstagramData($iftoken->iftoken_user_id, $iftoken->iftoken_access_code, $iftoken->iftoken_username, 4);
                    }
                    return view('themes.' . config('theme') . '.widgets.instagramLayout1', compact('collections', 'innerHtml'))->render();
                });
                $pageHtml = preg_replace('/<instagramlayout1(.*?)<\/instagramlayout1>/s', $Html, $pageHtml, 1);
            }
        }
        return $pageHtml;
       
    }

    public static function loadInstagramLayout2Feed($pageHtml, $pageId)
    {
        
        $tempHtml = $pageHtml;
        $innerHtml = true;
        preg_match_all('/yk-instagramLayout2(.*?)<\/section>/s', $tempHtml, $instagramLayout2Instances);
        if (!empty($instagramLayout2Instances[0])) {
            foreach ($instagramLayout2Instances[0] as $key => $instagram) {
                $Html =  \Cache::rememberForever('insta-section2-' . $pageId . '-' . $key, function () use ($innerHtml, $pageId) {
                    $iftoken = InstagramFeedToken::getValidTokenData();
                    $collections = [];
                    if (!empty($iftoken->iftoken_access_code)) {
                        $collections = SocialHelper::getInstagramData($iftoken->iftoken_user_id, $iftoken->iftoken_access_code, $iftoken->iftoken_username, 16);
                    }
                    return view('themes.' . config('theme') . '.widgets.instagramLayout2', compact('collections', 'innerHtml'))->render();
                });
                $pageHtml = preg_replace('/<instagramlayout2(.*?)<\/instagramlayout2>/s', $Html, $pageHtml, 1);
            }
        }
        return $pageHtml;
       
    }

    public static function loadInstagramCollection3Feed($pageHtml, $pageId)
    {
            
                $tempHtml = $pageHtml;
                $innerHtml = true;
                preg_match_all('/yk-instagramCollection3(.*?)<\/section>/s', $tempHtml, $instances);
                if (!empty($instances[0])) {
                    foreach ($instances[0] as $key => $record) {
                        $Html =  \Cache::rememberForever('insta-section3-' . $pageId . '-' . $key, function () use ($innerHtml, $pageId) {
                            $iftoken = InstagramFeedToken::getValidTokenData();
                            $collections = [];
                            if (!empty($iftoken->iftoken_access_code)) {
                                $collections = SocialHelper::getInstagramData($iftoken->iftoken_user_id, $iftoken->iftoken_access_code, $iftoken->iftoken_username, 6);
                            }
                             return view('themes.' . config('theme') . '.widgets.instagramCollection3', compact('collections', 'innerHtml'))->render();
                        });
                        $pageHtml = preg_replace('/<instagramcollection3(.*?)<\/instagramcollection3>/s', $Html, $pageHtml, 1);
                    }
                }
                return $pageHtml;
          
    }
    
    public static function highlightedText()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_HIGHLIGHTED_TEXT");
        return self::getBlock($layout, $title);
    }

    public static function twoColumnTextCta()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_TWOCOLUMNTEXTCTA");
        return self::getBlock($layout, $title);
    }

    public static function twoColumnTextCtaContent($pageid, $cid, $layout)
    {
        $collections = [];
        $collection_id = Collection::generateCollectionId($pageid, $cid, $layout);
        $collectionMetas = CollectionMeta::getValue($collection_id, ['TWOCOLUMNTEXTCTA_CTA_LABEL', 'TWOCOLUMNTEXTCTA_CTA_LINK', 'TWOCOLUMNTEXTCTA_CTA_TARGET']);

        $collections['cta_label'] = $collectionMetas['TWOCOLUMNTEXTCTA_CTA_LABEL'];
        $collections['cta_link'] = $collectionMetas['TWOCOLUMNTEXTCTA_CTA_LINK'];
        $collections['cta_target'] = $collectionMetas['TWOCOLUMNTEXTCTA_CTA_TARGET'];
        return view('themes.' . config('theme') . '.widgets.twoColumnTextCta', compact('collections', 'cid'))->render();
    }
    
    public static function titleWithBackgroundImage()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_TITLE_WITH_BACKGROUND_IMAGE");
        return self::getBlock($layout, $title);
    }

    public static function titleWithBackgroundImageContent($pageid, $cid, $layout)
    {
        $collections = [];
        $collection_id = Collection::generateCollectionId($pageid, $cid, $layout);
        $collections['text1'] = CollectionMeta::getValue($collection_id, 'TITLEWITHBACKGROUNDIMAGE_TEXT1');
        $collections['banner'] = AttachedFile::getFileUrl('titleWithBackgroundImage', $collection_id, 0, 'thumb');
        if (empty($collections['banner'])) {
            $collections['banner'] = noImage("4_1/2000x500.png");
        }
        return view('themes.' . config('theme') . '.widgets.titleWithBackgroundImage', compact('collections', 'cid'))->render();
    }

    public static function imageGallery()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_IMAGE_GALLERY");
        return self::getBlock($layout, $title);
    }

    public static function imageGalleryContent($pageid, $cid, $layout)
    {
        $collections = [];
        foreach ([1,2,3,4] as $record) {
            $collection_id = Collection::generateCollectionId($pageid, $cid, $layout, $record);
            $collections[$record]['banner_cropped'] = AttachedFile::getFileUrl('imageGallery', $collection_id, 0, 'thumb');
        }
        return view('themes.' . config('theme') . '.widgets.imageGallery', compact('collections', 'cid'))->render();
    }

    public static function textWithTwoImagesOnLeft()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_TEXTWITHTWOIMAGESONLEFT");
        return self::getBlock($layout, $title);
    }

    public static function textWithTwoImagesOnLeftContent($pageid, $cid, $layout)
    {
        $collections = [];
        foreach ([1,2] as $record) {
            $collection_id = Collection::generateCollectionId($pageid, $cid, $layout, $record);
            $collections[$record]['banner_cropped'] = AttachedFile::getFileUrl('textWithTwoImagesOnLeft', $collection_id, 0, 'thumb');
        }
        return view('themes.' . config('theme') . '.widgets.textWithTwoImagesOnLeft', compact('collections', 'cid'))->render();
    }

    public static function textWithTwoImagesOnRight()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_TEXTWITHTWOIMAGESONRIGHT");
        return self::getBlock($layout, $title);
    }

    public static function textWithTwoImagesOnRightContent($pageid, $cid, $layout)
    {
        $collections = [];
        foreach ([1,2] as $record) {
            $collection_id = Collection::generateCollectionId($pageid, $cid, $layout, $record);
            $collections[$record]['banner_cropped'] = AttachedFile::getFileUrl('textWithTwoImagesOnRight', $collection_id, 0, 'thumb');
        }
        return view('themes.' . config('theme') . '.widgets.textWithTwoImagesOnRight', compact('collections', 'cid'))->render();
    }

    
    public static function hrTag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_HR_TAG");
        return self::getBlock($layout, $title);
    }

    public static function customMap()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_CUSTOM_MAP");
        return self::getBlock($layout, $title);
    }

    public static function customMapContent($pageid, $cid, $layout)
    {
        $collections = [];
        $collection_id = Collection::generateCollectionId($pageid, $cid, $layout);
        $collections['map_script'] = CollectionMeta::getValue($collection_id, 'MAP_SCRIPT');
        return view('themes.' . config('theme') . '.widgets.partials.customMap', compact('collections', 'cid'))->render();
    }

    public static function categoryCollection3()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_CATEGORYCOLLECTION3");
        $params = ['innerHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function categoryCollection3Content($pageid, $cid, $layout, $innerHtml = false)
    {
        $collections = ProductCategory::getCategoryCollections($pageid, $cid, AttachedFile::SECTIONS['categoryCollection3']);

   
        return view('themes.' . config('theme') . '.widgets.categoryCollection3', compact('collections', 'cid', 'innerHtml'))->render();
    }

    public static function categoryCollection4()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_CATEGORYCOLLECTION4");
        $params = ['innerHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function categoryCollection4Content($pageid, $cid, $layout, $innerHtml = false)
    {
        $collections = ProductCategory::getCategoryCollections($pageid, $cid, AttachedFile::SECTIONS['categoryCollection4']);

    
        return view('themes.' . config('theme') . '.widgets.categoryCollection4', compact('collections', 'cid', 'innerHtml'))->render();
    }

    public static function testimonialCollection2()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_TESTIMONIALCOLLECTION2");
        $params = ['fromEditor' => true, 'innerHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function testimonialCollection2Content($pageid, $cid, $fromEditor = false, $innerHtml = false)
    {
        $testimonialIds = Collection::getRecordIdsByCid($pageid, $cid);
        $query = Testimonial::select(
            'testimonial_id',
            'testimonial_user_name',
            'testimonial_designation',
            'testimonial_title',
            'testimonial_description',
            'testimonial_updated_at',
            DB::raw("DATE_FORMAT(testimonial_created_at, '%d %b, %Y') as testimonial_created_at"),
            'af.afile_id'
        )
        ->leftJoin('attached_files AS af', function ($join) {
            $join->on('testimonial_id', '=', 'af.afile_record_id');
            $join->where('af.afile_type', AttachedFile::getConstantVal('testimonialMedia'));
        })
        ->where('testimonial_publish', config("constants.YES"))->whereIn('testimonial_id', $testimonialIds);
        if (!empty($testimonialIds) && count($testimonialIds) > 0) {
            $orderByIds = implode(',', $testimonialIds);
            $query->orderByRaw("FIELD(testimonial_id, $orderByIds)");
        }
        $collections = $query->get();
        return view('themes.' . config('theme') . '.widgets.testimonialCollection2', compact('collections', 'cid', 'fromEditor', 'innerHtml'))->render();
    }

    public static function promotionalBannerCollection2()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_PROMOTIONALBANNERCOLLECTION2");
        return self::getBlock($layout, $title);
    }

    public static function promotionalBannerCollection2Content($pageid, $cid, $layout)
    {
        $collections = [];
        foreach ([1,2] as $record) {
            $collection_id = Collection::generateCollectionId($pageid, $cid, $layout, $record);
            $collections[$record]['cropped'] = AttachedFile::getFileUrl('promotionalBannerCollection2', $collection_id, 0, '700-526');
            $collections[$record]['webp'] = AttachedFile::getFileUrl('promotionalBannerCollection2', $collection_id, 0, '700-526-webp');
            $collectionMetas = CollectionMeta::getValue($collection_id, ['PROMOTIONAL_BANNER_LINK', 'PROMOTIONAL_BANNER_TARGET']);
            $collections[$record]['link'] = $collectionMetas['PROMOTIONAL_BANNER_LINK'];
            $collections[$record]['target'] = $collectionMetas['PROMOTIONAL_BANNER_TARGET'];
        }
        
        return view('themes.' . config('theme') . '.widgets.promotionalBannerCollection2', compact('collections', 'cid'))->render();
    }

    public static function newsletterCollection2()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_NEWSLETTERCOLLECTION2");
        return self::getBlock($layout, $title);
    }

    public static function newsletterCollection2Content($pageid, $cid, $layout)
    {
        $collections = [];
        $collection_id = Collection::generateCollectionId($pageid, $cid, $layout);
        $collections['text'] = CollectionMeta::getValue($collection_id, 'NEWSLETTERCOLLECTION2_TEXT');
        $collections['banner'] = AttachedFile::getFileUrl('newsletterCollection2', $collection_id, 0, '1800-300-webp');
        return view('themes.' . config('theme') . '.widgets.newsletterCollection2', compact('collections', 'cid'))->render();
    }
    
    public static function blogCollection3()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_BLOGCOLLECTION3");
        $params = ['innerHtml' => false, 'frontend' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function blogCollection3Content($pageid, $cid, $layout, $innerHtml = false, $frontend = false)
    {
        $postIds = Collection::getRecordIdsByCid($pageid, $cid);
        $collections = BlogPost::blogCollectionRecords($postIds);
        return view('themes.' . config('theme') . '.widgets.blogCollection3', compact('collections', 'cid', 'innerHtml', 'frontend'))->render();
    }
    
    public static function faqCollection1()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_FAQCOLLECTION1");
        $params = ['innerHtml' => false];
        return self::getBlock($layout, $title, $params);
    }

    public static function faqCollection1Content($pageid, $cid, $layout, $innerHtml = false)
    {
       
            $collections = Faq::getCollections($cid, $pageid);
            return view('themes.' . config('theme') . '.widgets.faqCollection1', compact('collections', 'cid', 'innerHtml'))->render();
        
    }
}
