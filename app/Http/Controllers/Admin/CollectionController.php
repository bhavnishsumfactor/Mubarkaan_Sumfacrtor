<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\Admin\AdminYokartModel;
use App\Models\Page;
use App\Models\Configuration;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\NavigationMenu;
use App\Models\CollectionMeta;
use App\Models\Slide;
use App\Models\AttachedFile;
use App\Models\BlogPost;
use App\Models\Testimonial;
use App\Models\CollectionLabel;
use App\Models\Faq;
use App\Helpers\ThemeHelper;
use App\Helpers\FashionHelper;
use App\Models\Theme;
use DB;

class CollectionController extends AdminYokartController
{
    private $themeSlug;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $theme = Theme::getActiveTheme();
        $this->themeSlug = !empty($theme->theme_slug) ? $theme->theme_slug : config('constants.THEME');
        config(['theme' => $this->themeSlug]);
    }

    public function loadTemplate(Request $request, $page_id)
    {
        $page = Page::where('page_id', $page_id)->first();
        $configurations = getConfigValues([
            'FRONTEND_LOGO_RATIO',
            'HEADER_HTML',
            'FOOTER_HTML'
        ]);
        $baseUrl = url('') . '/';
        $headerHtml = $footerHtml = '';
        $headerContent = json_decode($configurations['HEADER_HTML'], true);
        $headerHtml = isset($headerContent['gjs-html']) ? $headerContent['gjs-html'] : $configurations['HEADER_HTML'];
        $footerContent = json_decode($configurations['FOOTER_HTML'], true);
        $footerHtml = isset($footerContent['gjs-html']) ? $footerContent['gjs-html'] : $configurations['FOOTER_HTML'];
        if (!empty($page)) {
            $overallPageContent = json_decode($page->page_content, true);

            $headerContent['gjs-components'] = str_replace('BASE_URL', $baseUrl, $headerContent['gjs-components']);
            $overallPageContent['gjs-components'] = str_replace('BASE_URL', $baseUrl, $overallPageContent['gjs-components']);
            $footerContent['gjs-components'] = str_replace('BASE_URL', $baseUrl, $footerContent['gjs-components']);
            $overallPageContent['gjs-css'] = str_replace('BASE_URL', $baseUrl, $overallPageContent['gjs-css']);
            
            /* $pageHtml = ''; // For new page, use below code.
            if ($pageHtml == '') {
                Configuration::saveValue('HEADER_HTML', '');
                Configuration::saveValue('FOOTER_HTML', '');
                // Page::where('page_id', $page_id)->update(['page_content' => '']);
                $pageHtml = '<div class="body js-body" id="body" role="main">'.$pageHtml.'</div>';
            }
            unset($overallPageContent);*/
            $defaultHeaderComponents = '';
            // {"tagName":"header","type":"default","removable":false,"draggable":"div#wrapper","content":"    \n","classes":["header"],"attributes":{"id":"header","data-yk":""},"open":0}
            $defaultBodyComponents = '{"classes":["body","js-body"],"attributes":{"id":"body","role":"main"},"open":1}';
            $defaultFooterComponents = '';
            return response()->json([
                'html' => ($pageHtml ?? ''),
                'header' => !empty($headerContent['gjs-components']) ? $headerContent['gjs-components'] : $defaultHeaderComponents,
                'body' => !empty($overallPageContent['gjs-components']) ? $overallPageContent['gjs-components'] : $defaultBodyComponents,
                'footer' => !empty($footerContent['gjs-components']) ? $footerContent['gjs-components'] : $defaultFooterComponents,
                'css' => ($overallPageContent['gjs-css'] ?? '')
            ]);
        }
    }

    public function saveTemplate(Request $request, $page_id)
    {
        $requestContent = json_decode($request->getContent(), true);
        
        $bodyContent = $headerContent = $footerContent = [];

        $baseUrl = url('') . '/';
        
        $headerContent['gjs-html'] = str_replace($baseUrl, 'BASE_URL', $requestContent["html"]["header"]);
        $bodyContent['gjs-html'] = str_replace($baseUrl, 'BASE_URL', $requestContent["html"]["body"]);
        $footerContent['gjs-html'] = str_replace($baseUrl, 'BASE_URL', $requestContent["html"]["footer"]);
        
        $headerContent['gjs-components'] = str_replace($baseUrl, 'BASE_URL', $requestContent["components"]["header"]);
        $bodyContent['gjs-components'] = str_replace($baseUrl, 'BASE_URL', $requestContent["components"]["body"]);
        $footerContent['gjs-components'] = str_replace($baseUrl, 'BASE_URL', $requestContent["components"]["footer"]);
        $requestContent["css"] = str_replace($baseUrl, 'BASE_URL', $requestContent["css"]);
        
        $bodyContent['gjs-css'] = str_replace('*{box-sizing:border-box;}body{margin:0;}', '', $requestContent["css"]);
        
        Page::where('page_id', $page_id)->update(['page_content' => json_encode($bodyContent)]);
        
        if (Configuration::getValue('GETSTARTED_SKIP') == 0) {
            $page = Page::select('page_type')->where('page_id', $page_id)->first();
            if ($page->page_type == '1') {
                Configuration::saveValue('GETSTARTED_HOMEPAGE', 1);
            } elseif (in_array($page->page_type, ['2', '5', '6'])) {
                Configuration::saveValue('GETSTARTED_CONTENTPAGES', 1);
            }
        }
        \Cache::forget('header-section');
        \Cache::forget('footer-section');
        
        if ($page_id == 1) {
            Configuration::bulkUpdate([
                'HEADER_HTML' => json_encode($headerContent),
                'FOOTER_HTML' => json_encode($footerContent)
            ]);
        }
        \Artisan::call("cache:clear");
        return jsonresponse(true, __('NOTI_TEMPLATE_SAVED'));
    }

    public function loadWidget(Request $request)
    {
        $themeClass = 'App\Helpers\\' . ucwords($this->themeSlug) . 'Helper';
        $layout = $request->input('layout');
        $cid = !empty($request->input('cid')) ? $request->input('cid') : '';
        $pageid = self::pageId($request);
        $param = !empty($request->input('param')) ? $request->input('param') : '';
        $save = ($request->input('save') != null) ? (bool) $request->input('save') : false;
        $widgets = FashionHelper::getWidgetsFlipped();
        if ($save === true) {
            $this->storeCollectionsData($request, $pageid, $cid, $widgets, $layout);
        }
        switch ($layout) {
            case 'brands':
                  $html = $themeClass::brandsContent($pageid, $cid, true, false);
                break;

            case 'productCollectionLayout1':
                  $html = $themeClass::productCollectionLayout1Content($pageid, $cid, '', false, true);
                break;

            case 'categoryCollectionLayout1':
                  $html = $themeClass::categoryCollectionLayout1Content($pageid, $cid, false, true);
                  $tabbingHtml = $themeClass::categoryCollectionLayout1Categories($pageid, $cid);
                break;

            case 'categoryCollectionLayout2':
                  $html = $themeClass::categoryCollectionLayout2Content($pageid, $cid, $widgets[$layout]);
                break;

            case 'productCollectionLayout2':
                  $html = $themeClass::productCollectionLayout2Content($pageid, $cid, '', false, true);
                break;

            case 'navigationLayout1':
                  $html = $themeClass::navigationLayout1Content($pageid, $cid);
                break;

            case 'bannerSlider':
                  $html = $themeClass::bannerSliderContent($pageid, $cid, $widgets[$layout], true);
                break;

            case 'promotionalBannerLayout1':
                  $html = $themeClass::promotionalBannerLayout1Content($pageid, $cid, $widgets[$layout]);
                break;

            case 'newsletterFullwidth':
                  $html = $themeClass::newsletterFullwidthContent($pageid, $cid, $widgets[$layout]);
                break;

            case 'instagramLayout1':
                  $html = $themeClass::instagramLayout1Content($pageid, $cid, $widgets[$layout], $param);
                break;

            case 'instagramLayout2':
                  $html = $themeClass::instagramLayout2Content($pageid, $cid, $widgets[$layout], $param);
                break;

            case 'imageTag':
                  $html = ThemeHelper::imageTagContent($pageid, $cid, $widgets[$layout], $param);
                break;

            case 'videoTag':
                  $html = ThemeHelper::videoTagContent($pageid, $cid, $widgets[$layout], $param);
                break;

            case 'blogLayout1':
                  $html = $themeClass::blogLayout1Content($pageid, $cid, $widgets[$layout], $param);
                break;

            case 'blogLayout2':
                  $html = $themeClass::blogLayout2Content($pageid, $cid, $widgets[$layout], $param);
                break;

            case 'testimonialLayout1':
                  $html = $themeClass::testimonialLayout1Content($pageid, $cid, true, true);
                break;

            case 'testimonialCollection2':
                  $html = $themeClass::testimonialCollection2Content($pageid, $cid, true, true);
                break;
                \Cache::forget('home-banner-section');ontent($pageid, $cid, $widgets[$layout]);
                break;

            case 'titleWithBackgroundImage':
                    $html = $themeClass::titleWithBackgroundImageContent($pageid, $cid, $widgets[$layout]);
                break;

            case 'imageGallery':
                    $html = $themeClass::imageGalleryContent($pageid, $cid, $widgets[$layout]);
                break;

            case 'textWithTwoImagesOnLeft':
                    $html = $themeClass::textWithTwoImagesOnLeftContent($pageid, $cid, $widgets[$layout]);
                break;

            case 'textWithTwoImagesOnRight':
                    $html = $themeClass::textWithTwoImagesOnRightContent($pageid, $cid, $widgets[$layout]);
                break;

            case 'customMap':
                    $html = $themeClass::customMapContent($pageid, $cid, $widgets[$layout]);
                break;

            case 'categoryCollection3':
                $html = $themeClass::categoryCollection3Content($pageid, $cid, $widgets[$layout]);
                break;

            case 'categoryCollection4':
                $html = $themeClass::categoryCollection4Content($pageid, $cid, $widgets[$layout]);
                break;

            case 'instagramCollection3':
                    $html = $themeClass::instagramCollection3Content($pageid, $cid, $widgets[$layout], $param);
                break;

            case 'promotionalBannerCollection2':
                    $html = $themeClass::promotionalBannerCollection2Content($pageid, $cid, $widgets[$layout]);
                break;

            case 'newsletterCollection2':
                    $html = $themeClass::newsletterCollection2Content($pageid, $cid, $widgets[$layout]);
                break;

            case 'blogCollection3':
                    $html = $themeClass::blogCollection3Content($pageid, $cid, $widgets[$layout], $param);
                break;

            case 'faqCollection1':
                    $html = $themeClass::faqCollection1Content($pageid, $cid, $widgets[$layout], true);
                break;

            case 'promotionalBannerCollection3':
                    $html = $themeClass::promotionalBannerCollection3Content($pageid, $cid, $widgets[$layout]);
                break;

            default:
                  // code...
                break;
        }
        if (isset($tabbingHtml)) {
            return jsonresponse(true, null, [
                'html' => $html,
                'tabbingHtml' => $tabbingHtml
            ]);
        }
       
        return $html;
    }

    public function searchCollection(Request $request)
    {
        $searchTerm = $request->input('term');
        $layout = $request->input('layout');
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $widgets = FashionHelper::getWidgetsFlipped();
        switch ($layout) {
            case 'brands':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = Brand::getBrandsByName($searchTerm, $excludeIds);
                break;

            case 'productCollectionLayout1':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $searchTerm = str_replace(" ", "", $searchTerm);
                $records = Product::getProductsByName($searchTerm, $excludeIds);
                break;

            case 'categoryCollectionLayout1':
                if (!empty($request->input('categoryid'))) {
                    $excludeIds = Collection::getGroupedRecordIds($pageid, $cid, $widgets[$layout], $request->input('categoryid'));
                    $searchTerm = str_replace(" ", "", $searchTerm);
                    $records = Product::getProductsByName($searchTerm, $excludeIds, $request->input('categoryid'));
                } else {
                    $excludeIds = Collection::getGroupedRecordIds($pageid, $cid, $widgets[$layout]);
                    $records = ProductCategory::getCategoriesByName($searchTerm, $excludeIds);
                }
                break;

            case 'categoryCollectionLayout2':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = ProductCategory::getCategoriesByName($searchTerm, $excludeIds);
                break;

            case 'productCollectionLayout2':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $searchTerm = str_replace(" ", "", $searchTerm);
                $records = Product::getProductsByName($searchTerm, $excludeIds);
                break;

            case 'navigationLayout1':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = [];
                break;

            case 'blogLayout1':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = BlogPost::getPostsByName($searchTerm, $excludeIds);
                break;

            case 'blogLayout2':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = BlogPost::getPostsByName($searchTerm, $excludeIds);
                break;

            case 'testimonialLayout1':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = Testimonial::getRecordsByName($searchTerm, $excludeIds);
                break;

            case 'testimonialCollection2':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = Testimonial::getRecordsByName($searchTerm, $excludeIds);
                break;

            case 'categoryCollection3':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = ProductCategory::getCategoriesByName($searchTerm, $excludeIds);
                break;

            case 'categoryCollection4':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = ProductCategory::getCategoriesByName($searchTerm, $excludeIds);
                break;

            case 'blogCollection3':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = BlogPost::getPostsByName($searchTerm, $excludeIds);
                break;

            case 'faqCollection1':
                $excludeIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                $records = Faq::getRecordsByName($searchTerm, $excludeIds);
                break;

            default:
                $records = [];
                break;
        }
        return $records;
    }

    public function saveCollection(Request $request)
    {
        $layout = $request->input('layout');
        $records = $request->input('records');
        $displayOrder = $request->input('display_order');
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $widgets = FashionHelper::getWidgetsFlipped();
        $data = $this->storeCollectionsData($request, $pageid, $cid, $widgets, $layout, $records, $displayOrder);
        return jsonresponse(true, __('NOTI_COLLECTION_SAVED'), $data);
    }

    private function storeCollectionsData($request, $pageid, $cid, $widgets, $layout, $records = 0, $displayOrder = 0)
    {
        $data = [];
        switch ($layout) {
            case 'brands':
                Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                break;

            case 'productCollectionLayout1':
                Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                break;

            case 'productCollectionLayout2':
                Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                break;

            case 'categoryCollectionLayout1':
                $categoryid = $request->input('categoryid');
                Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $categoryid, $records, $displayOrder);
                break;

            case 'promotionalBannerLayout1':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_TEXT1', $request->input('text1'));
                CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_TEXT2', $request->input('text2'));
                CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_CTA_LABEL', $request->input('cta_label'));
                CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_CTA_LINK', $request->input('cta_link'));
                CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_CTA_TARGET', $request->input('cta_target'));
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'newsletterFullwidth':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                CollectionMeta::saveOrUpdate($collection_id, 'NEWSLETTER_BANNER_TEXT1', $request->input('text1'));
                CollectionMeta::saveOrUpdate($collection_id, 'NEWSLETTER_BANNER_TEXT2', $request->input('text2'));
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'categoryCollectionLayout2':
                $categories = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                if (!$request->hasFile('cropImage') && count($categories) == 6) {
                    return jsonresponse(false, __('NOTI_ATMOST_6_CATEGORIES_CAN_BE_SELECTED'));
                }
                $categories = ProductCategory::getCategoriesById($categories)->toArray();
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'imageTag':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'videoTag':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                CollectionMeta::saveOrUpdate($collection_id, 'VIDEO_PROVIDER_TYPE', $request->input('provider_type'));
                CollectionMeta::saveOrUpdate($collection_id, 'VIDEO_URL', $request->input('video_url'));
                $autoplay = ($request->input('autoplay') == 'true') ? '1' : '0';
                CollectionMeta::saveOrUpdate($collection_id, 'VIDEO_AUTOPLAY', $autoplay);
                break;

            case 'blogLayout1':
                Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                break;

            case 'blogLayout2':
                Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                break;

            case 'testimonialLayout1':
                Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                break;

            case 'testimonialCollection2':
                Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                break;

            case 'twoColumnTextCta':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                CollectionMeta::saveOrUpdate($collection_id, 'TWOCOLUMNTEXTCTA_CTA_LABEL', $request->input('cta_label'), true);
                CollectionMeta::saveOrUpdate($collection_id, 'TWOCOLUMNTEXTCTA_CTA_LINK', $request->input('cta_link'), true);
                CollectionMeta::saveOrUpdate($collection_id, 'TWOCOLUMNTEXTCTA_CTA_TARGET', $request->input('cta_target'));
                break;

            case 'titleWithBackgroundImage':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                CollectionMeta::saveOrUpdate($collection_id, 'TITLEWITHBACKGROUNDIMAGE_TEXT1', $request->input('text1'));
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'imageGallery':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records);
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'textWithTwoImagesOnLeft':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records);
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'textWithTwoImagesOnRight':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records);
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'customMap':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                CollectionMeta::saveOrUpdate($collection_id, 'MAP_SCRIPT', $request->input('map_script'));
                break;
                
            case 'navigationLayout1':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                CollectionMeta::saveOrUpdate($collection_id, 'WELCOME_TEXT', $request->input('welcome_text'));
                break;

            case 'categoryCollection3':
                $categories = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                if (!$request->hasFile('cropImage') && count($categories) == 6) {
                    return jsonresponse(false, __('NOTI_EXACTLY_6_CATEGORIES_REQUIRED'));
                }
                $categories = ProductCategory::getCategoriesById($categories)->toArray();
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'categoryCollection4':
                $categories = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
                if (!$request->hasFile('cropImage') && count($categories) == 6) {
                    return jsonresponse(false, __('NOTI_EXACTLY_6_CATEGORIES_REQUIRED'));
                }
                $categories = ProductCategory::getCategoriesById($categories)->toArray();
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'promotionalBannerCollection2':
                if (!empty($records)) {
                    $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records);
                    imageUpload($request, $collection_id, $layout);
                    $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                    $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                } else {
                    $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], 1);
                    CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_LINK', $request->input('link_1'));
                    CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_TARGET', $request->input('target_1'));

                    $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], 2);
                    CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_LINK', $request->input('link_2'));
                    CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_TARGET', $request->input('target_2'));
                }
                break;

            case 'newsletterCollection2':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                CollectionMeta::saveOrUpdate($collection_id, 'NEWSLETTERCOLLECTION2_TEXT', $request->input('text'));
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;

            case 'blogCollection3':
                Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                break;

            case 'faqCollection1':
                Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $records, 0, $displayOrder);
                break;

            case 'promotionalBannerCollection3':
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_LINK', $request->input('link'));
                CollectionMeta::saveOrUpdate($collection_id, 'PROMOTIONAL_BANNER_TARGET', $request->input('target'));
                imageUpload($request, $collection_id, $layout);
                $data['url'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'thumb');
                $data['originalUrl'] = AttachedFile::getFileUrl($layout, $collection_id, 0, 'original');
                break;
                
            default:
                // code...
                break;
        }
        return $data;
    }

    private function reOrderCollections($pageid, $cid, $layout, $recordId = 0)
    {
        $collectionData = Collection::getRecords($pageid, $cid, $layout, $recordId)->toArray();
        $i = 1;
        if (!empty($collectionData)) {
            foreach ($collectionData as $v) {
                Collection::where('collection_id', $v)->update(['collection_display_order' => $i]);
                $i++;
            }
        }
        return $i - 1; //return the highest order of current collection after reordering
    }

    public function deleteCollection(Request $request)
    {
        $layout = $request->input('layout');
        $record = $request->input('record');
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $widgets = FashionHelper::getWidgetsFlipped();
        $html = $highestOrder = '';
        switch ($layout) {
            case 'brands':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'productCollectionLayout1':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'categoryCollectionLayout1':
                if (!empty($record)) {
                    Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $request->input('categoryid'), $record);
                    $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout], $request->input('categoryid'));
                    $request['updateListing'] = 1;
                    $request['categoryid'] = $request->input('categoryid');
                    $html = $this->getCollection($request);
                } else {
                    Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $request->input('categoryid'));
                }
                break;

            case 'categoryCollectionLayout2':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'productCollectionLayout2':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'navigationLayout1':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                break;

            case 'blogLayout1':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'blogLayout2':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'testimonialLayout1':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'testimonialCollection2':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'categoryCollection3':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'categoryCollection4':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'blogCollection3':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            case 'faqCollection1':
                Collection::deleteIfExists($pageid, $cid, $widgets[$layout], $record);
                $highestOrder = $this->reOrderCollections($pageid, $cid, $widgets[$layout]);
                $request['updateListing'] = 1;
                $html = $this->getCollection($request);
                break;

            default:
                // code...
                break;
        }
        return jsonresponse(true, __('NOTI_COLLECTION_RECORD_DELETED'), ['html' => $html, 'highestOrder' => $highestOrder]);
    }

    public function deleteAllCollection(Request $request)
    {
        DB::beginTransaction();
        try {
            $cid = $request->input('cid');
            $pageid = self::pageId($request);
            $query = Collection::where('collection_page_id', $pageid)->where('collection_component_id', $cid);
            $collectionIds = $query->pluck('collection_id')->toArray();
            $componentIds = $query->pluck('collection_component_id')->toArray();
            $query->delete();
            CollectionMeta::whereIn('cmeta_collection_id', $collectionIds)->delete();
            Slide::whereIn('slide_collection_id', $collectionIds)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return jsonresponse(true, __('NOTI_COLLECTION_DELETED'));
    }

    public function getCollection(Request $request)
    {
        $layout = $request->input('layout');
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $widgets = FashionHelper::getWidgetsFlipped();

        $updateListing = !empty($request->input('updateListing')) ? $request->input('updateListing') : 0;
        $categoryId = !empty($request->input('categoryid')) ? $request->input('categoryid') : 0;

        if ($updateListing == 1 && !empty($request->input('newPosition')) && !empty($request->input('recordId')) && !empty($request->input('movement'))) { /* update display order in collections upon dragging */
            $displayOrder = $request->input('newPosition');
            $recordId = $request->input('recordId');
            $movement = $request->input('movement');
            
            if ($widgets[$layout] == 3) {
                /*category collection layout 1 */
                $sourceRecord = Collection::getRecord($pageid, $cid, $widgets[$layout], null, $recordId);
                $categoryId = $sourceRecord->collection_record_id;
                $destinationRecord = Collection::getRecord($pageid, $cid, $widgets[$layout], $categoryId, null, $displayOrder);
                $collectionData = Collection::getRecords($pageid, $cid, $widgets[$layout], $categoryId)->toArray();
                $sourceId = $sourceRecord->collection_id;
                $destinationId = $destinationRecord->collection_id;
            } elseif ($widgets[$layout] == 5) {
                $collection = Collection::getCollectionIds($pageid, $cid, $widgets[$layout]);
                $collectionData = NavigationMenu::getMenusByCollectionId($collection[0])->pluck('navmenu_id')->toArray();
                $destinationRecord = NavigationMenu::getMenuByDisplayOrder($collection[0], $displayOrder);
                $sourceId = $recordId;
                $destinationId = $destinationRecord->navmenu_id;
            } else {
                $sourceRecord = Collection::getRecord($pageid, $cid, $widgets[$layout], $recordId);
                $destinationRecord = Collection::getRecord($pageid, $cid, $widgets[$layout], null, null, $displayOrder);
                $collectionData = Collection::getRecords($pageid, $cid, $widgets[$layout])->toArray();
                $sourceId = $sourceRecord->collection_id;
                $destinationId = $destinationRecord->collection_id;
            }
            $sourceIndex = array_search($sourceId, $collectionData);
            
            if (in_array($sourceId, $collectionData)) {
                unset($collectionData[$sourceIndex]);
            }
            $collectionData = array_values($collectionData);
            $destinationIndex = array_search($destinationId, $collectionData);
            if ($movement == 'down') {
                $destinationIndex = $destinationIndex + 1;
            }
            array_splice($collectionData, $destinationIndex, 0, $sourceId);
            
            $i = 1;
            if (!empty($collectionData)) {
                foreach ($collectionData as $v) {
                    if ($widgets[$layout] == 5) {
                        NavigationMenu::where('navmenu_id', $v)->update(['navmenu_display_order' => $i]);
                    } else {
                        Collection::where('collection_id', $v)->update(['collection_display_order' => $i]);
                    }
                    $i++;
                }
            }
        }
        $recordIds = Collection::getRecordIds($pageid, $cid, $widgets[$layout]);
        switch ($layout) {
            case 'brands':
                $data = Brand::getBrandsById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'productCollectionLayout1':
                $data = Product::getProductsById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'productCollectionLayout2':
                $data = Product::getProductsById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                $collection = CollectionLabel::getData($cid);
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'collection', 'recordIds', 'updateListing'))->render();
                break;

            case 'categoryCollectionLayout1':
                $data = Collection::getCollectionsInHierarchy($pageid, $cid, $widgets[$layout], $categoryId, false);
               
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'updateListing'))->render();
                break;

            case 'categoryCollectionLayout2':
                $data = ProductCategory::getCategoriesById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                if (!empty($data)) {
                    foreach ($data as $key => $category) {
                        $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $category['value']);
                        $bannerSlide = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('categoryCollectionLayout2'), $collection_id);
                        $data[$key]['banner_actual'] = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/original?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                        $data[$key]['banner_cropped'] = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/88-88?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                    }
                }
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'navigationLayout1':
                $data = [];
                // $collection = Collection::getCollectionIds($pageid, $cid, $widgets[$layout]);
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                if (!empty($collection_id)) {
                    $data = NavigationMenu::getMenusByCollectionId($collection_id);
                }
                $types = NavigationMenu::MENU_TYPE;
                $menuNumber = 'One';
                $welcome_text = CollectionMeta::getValue($collection_id, 'WELCOME_TEXT');
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'types', 'menuNumber', 'updateListing', 'welcome_text'))->render();
                break;

            case 'bannerSlider':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $data['slider_duration'] = CollectionMeta::getValue($collection_id, 'BANNER_SLIDER_DURATION');
                $data['slides'] = Slide::getSlides($collection_id);
                if (!empty($data['slides'])) {
                    foreach ($data['slides'] as $key => $slide) {
                        $bannerSlide = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('bannerSlider'), $collection_id, $slide->slide_id, AttachedFile::DEVICE_TYPE['desktop']);
                        $data['slides'][$key]->desktop_actual = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/original?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                        $data['slides'][$key]->desktop_cropped = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/195-49-webp?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                        $bannerSlide = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('bannerSlider'), $collection_id, $slide->slide_id, AttachedFile::DEVICE_TYPE['tablet']);
                        $data['slides'][$key]->tablet_actual = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/original?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                        $data['slides'][$key]->tablet_cropped = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/195-130-webp?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                        $bannerSlide = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('bannerSlider'), $collection_id, $slide->slide_id, AttachedFile::DEVICE_TYPE['mobile']);
                        $data['slides'][$key]->mobile_actual = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/original?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                        $data['slides'][$key]->mobile_cropped = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/195-147-webp?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                    }
                }
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();


                break;

            case 'promotionalBannerLayout1':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $collectionMetas = CollectionMeta::getValue($collection_id, [
                    'PROMOTIONAL_BANNER_TEXT1',
                    'PROMOTIONAL_BANNER_TEXT2',
                    'PROMOTIONAL_BANNER_CTA_LABEL',
                    'PROMOTIONAL_BANNER_CTA_LINK',
                    'PROMOTIONAL_BANNER_CTA_TARGET'
                    ]);
                    
                $data['text1'] = $collectionMetas['PROMOTIONAL_BANNER_TEXT1'] ?? '';
                $data['text2'] = $collectionMetas['PROMOTIONAL_BANNER_TEXT2'] ?? '';
                $data['cta_label'] = $collectionMetas['PROMOTIONAL_BANNER_CTA_LABEL'] ?? '';
                $data['cta_link'] = $collectionMetas['PROMOTIONAL_BANNER_CTA_LINK'] ?? '';
                $data['cta_target'] = $collectionMetas['PROMOTIONAL_BANNER_CTA_TARGET'] ?? '';
                $banner = AttachedFile::getAttachedFile(AttachedFile::getConstantVal($layout), $collection_id);
                $data['banner_actual'] = !empty($banner->afile_id) ? url('yokart/image/' . $banner->afile_id . '/original?t=' . strtotime($banner->afile_updated_at)) : '';
                $data['banner_cropped'] = !empty($banner->afile_id) ? url('yokart/image/' . $banner->afile_id . '/88-44?t=' . strtotime($banner->afile_updated_at)) : '';
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'newsletterFullwidth':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);

                $collectionMetas = CollectionMeta::getValue($collection_id, ['NEWSLETTER_BANNER_TEXT1', 'NEWSLETTER_BANNER_TEXT2']);

                $data['text1'] = $collectionMetas['NEWSLETTER_BANNER_TEXT1'] ?? '';
                $data['text2'] = $collectionMetas['NEWSLETTER_BANNER_TEXT2'] ?? '';
                $banner = AttachedFile::getAttachedFile(AttachedFile::getConstantVal($layout), $collection_id);
                $data['banner_actual'] = !empty($banner->afile_id) ? url('yokart/image/' . $banner->afile_id . '/original?t=' . strtotime($banner->afile_updated_at)) : '';
                $data['banner_cropped'] = !empty($banner->afile_id) ? url('yokart/image/' . $banner->afile_id . '/105-26?t=' . strtotime($banner->afile_updated_at)) : '';
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'instagramLayout1':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $data['handle'] = CollectionMeta::getValue($collection_id, 'INSTAGRAM_LAYOUT1_HANDLE');
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'instagramLayout2':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $data['handle'] = CollectionMeta::getValue($collection_id, 'INSTAGRAM_LAYOUT2_HANDLE');
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'imageTag':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $image = AttachedFile::getAttachedFile(AttachedFile::getConstantVal($layout), $collection_id);
                $data['image_actual'] = !empty($image->afile_id) ? url('yokart/image/' . $image->afile_id . '/original?t=' . strtotime($image->afile_updated_at)) : '';
                $data['image_cropped'] = !empty($image->afile_id) ? url('yokart/image/' . $image->afile_id . '/thumb?t=' . strtotime($image->afile_updated_at)) : '';
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'videoTag':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $collectionMetas = CollectionMeta::getValue($collection_id, ['VIDEO_PROVIDER_TYPE', 'VIDEO_URL', 'VIDEO_AUTOPLAY']);
                $data['provider_type'] = $collectionMetas['VIDEO_PROVIDER_TYPE'];
                $data['video_url'] = $collectionMetas['VIDEO_URL'];
                $data['autoplay'] = $collectionMetas['VIDEO_AUTOPLAY'];
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'blogLayout1':
                $data = BlogPost::getPostsById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'blogLayout2':
                $data = BlogPost::getPostsById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'testimonialLayout1':
                $data = Testimonial::getRecordsById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'testimonialCollection2':
                $data = Testimonial::getRecordsById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'twoColumnTextCta':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);

                $collectionMetas = CollectionMeta::getValue($collection_id, ['TWOCOLUMNTEXTCTA_CTA_LABEL', 'TWOCOLUMNTEXTCTA_CTA_LINK', 'TWOCOLUMNTEXTCTA_CTA_TARGET']);
                $data['cta_label'] = $collectionMetas['TWOCOLUMNTEXTCTA_CTA_LABEL'] ?? '';
                $data['cta_link'] = $collectionMetas['TWOCOLUMNTEXTCTA_CTA_LINK'] ?? '';
                $data['cta_target'] = $collectionMetas['TWOCOLUMNTEXTCTA_CTA_TARGET'] ?? '';
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'titleWithBackgroundImage':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $data['text1'] = CollectionMeta::getValue($collection_id, 'TITLEWITHBACKGROUNDIMAGE_TEXT1');
                $banner = AttachedFile::getAttachedFile(AttachedFile::getConstantVal($layout), $collection_id);
                $data['banner_actual'] = !empty($banner->afile_id) ? url('yokart/image/' . $banner->afile_id . '/original?t=' . strtotime($banner->afile_updated_at)) : '';
                $data['banner_cropped'] = !empty($banner->afile_id) ? url('yokart/image/' . $banner->afile_id . '/105-26?t=' . strtotime($banner->afile_updated_at)) : '';
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'imageGallery':
                $data = [];
                foreach ([1,2,3,4] as $record) {
                    $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $record);
                    $bannerSlide = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('imageGallery'), $collection_id);
                    $data[$record]['banner_actual'] = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/original?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                    $data[$record]['banner_cropped'] = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/thumb?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                }
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'textWithTwoImagesOnLeft':
                $data = [];
                foreach ([1,2] as $record) {
                    $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $record);
                    $bannerSlide = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('textWithTwoImagesOnLeft'), $collection_id);
                    $data[$record]['banner_actual'] = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/original?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                    $data[$record]['banner_cropped'] = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/105-140?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                }
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'textWithTwoImagesOnRight':
                $data = [];
                foreach ([1,2] as $record) {
                    $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $record);
                    $bannerSlide = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('textWithTwoImagesOnRight'), $collection_id);
                    $data[$record]['banner_actual'] = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/original?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                    $data[$record]['banner_cropped'] = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/105-140?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
                }
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'customMap':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $data['map_script'] = CollectionMeta::getValue($collection_id, 'MAP_SCRIPT');
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'categoryCollection3':
                $data = ProductCategory::getCategoriesById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                if (!empty($data)) {
                    foreach ($data as $key => $category) {
                        $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $category['value']);
                        $attachedFile = AttachedFile::getAttachedFile(AttachedFile::getConstantVal($layout), $collection_id);
                        $data[$key]['actual'] = !empty($attachedFile->afile_id) ? url('yokart/image/' . $attachedFile->afile_id . '/original?t=' . strtotime($attachedFile->afile_updated_at)) : '';
                        $data[$key]['cropped'] = !empty($attachedFile->afile_id) ? url('yokart/image/' . $attachedFile->afile_id . '/105-105?t=' . strtotime($attachedFile->afile_updated_at)) : '';
                    }
                }
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'categoryCollection4':
                $data = ProductCategory::getCategoriesById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                if (!empty($data)) {
                    foreach ($data as $key => $category) {
                        $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $category['value']);
                        $attachedFile = AttachedFile::getAttachedFile(AttachedFile::getConstantVal($layout), $collection_id);
                        $data[$key]['actual'] = !empty($attachedFile->afile_id) ? url('yokart/image/' . $attachedFile->afile_id . '/original?t=' . strtotime($attachedFile->afile_updated_at)) : '';
                        $data[$key]['cropped'] = !empty($attachedFile->afile_id) ? url('yokart/image/' . $attachedFile->afile_id . '/105-105?t=' . strtotime($attachedFile->afile_updated_at)) : '';
                    }
                }
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'instagramCollection3':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $data['handle'] = CollectionMeta::getValue($collection_id, 'INSTAGRAMCOLLECTION3_HANDLE');
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'promotionalBannerCollection2':
                $data = [];
                foreach ([1,2] as $record) {
                    $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $record);
                    $slide = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('promotionalBannerCollection2'), $collection_id);
                    $data[$record]['actual'] = !empty($slide->afile_id) ? url('yokart/image/' . $slide->afile_id . '/original?t=' . strtotime($slide->afile_updated_at)) : '';
                    $data[$record]['cropped'] = !empty($slide->afile_id) ? url('yokart/image/' . $slide->afile_id . '/105-79?t=' . strtotime($slide->afile_updated_at)) : '';

                    $collectionMetas = CollectionMeta::getValue($collection_id, ['PROMOTIONAL_BANNER_TARGET', 'PROMOTIONAL_BANNER_LINK']);
                    $data[$record]['target'] = $collectionMetas['PROMOTIONAL_BANNER_TARGET'] ?? '';
                    $data[$record]['link'] = $collectionMetas['PROMOTIONAL_BANNER_LINK'] ?? '';
                }
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'newsletterCollection2':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $data['text'] = CollectionMeta::getValue($collection_id, 'NEWSLETTERCOLLECTION2_TEXT');
                $banner = AttachedFile::getAttachedFile(AttachedFile::getConstantVal($layout), $collection_id);
                $data['actual'] = !empty($banner->afile_id) ? url('yokart/image/' . $banner->afile_id . '/original?t=' . strtotime($banner->afile_updated_at)) : '';
                $data['cropped'] = !empty($banner->afile_id) ? url('yokart/image/' . $banner->afile_id . '/88-15?t=' . strtotime($banner->afile_updated_at)) : '';
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            case 'blogCollection3':
                $data = BlogPost::getPostsById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'faqCollection1':
                $data = Faq::getRecordsById($recordIds)->toArray();
                $recordIds = array_flip($recordIds);
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid', 'recordIds', 'updateListing'))->render();
                break;

            case 'promotionalBannerCollection3':
                $data = [];
                $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
                $data['link'] = CollectionMeta::getValue($collection_id, 'PROMOTIONAL_BANNER_LINK');
                $data['target'] = CollectionMeta::getValue($collection_id, 'PROMOTIONAL_BANNER_TARGET');
                $banner = AttachedFile::getAttachedFile(AttachedFile::getConstantVal($layout), $collection_id);
                $data['actual'] = !empty($banner->afile_id) ? url('yokart/image/' . $banner->afile_id . '/original?t=' . strtotime($banner->afile_updated_at)) : '';
                $data['cropped'] = !empty($banner->afile_id) ? url('yokart/image/' . $banner->afile_id . '/88-22?t=' . strtotime($banner->afile_updated_at)) : '';
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.' . $layout, compact('data', 'cid'))->render();
                break;

            default:
                $html = '';
                break;
        }
        return $html;
    }

    public function getCollectionSetting(Request $request)
    {
        $layout = $request->input('layout');
        switch ($layout) {
            case 'navigationLayout1':
                $menuNumber = $request->input('params');
                $displayOrder = $request->input('displayOrder');
                $types = NavigationMenu::MENU_TYPE;
                $item = [];
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.partials.' . $layout, compact('item', 'types', 'menuNumber', 'displayOrder'))->render();
                break;

            case 'bannerSlider':
                $newTabNumber = $request->input('params');
                $k = $request->input('currentTabCount');
                $item = [];
                $html = view('themes.' . $this->themeSlug . '.widgets.settings.partials.' . $layout, compact('k', 'item', 'newTabNumber'))->render();
                break;

            default:
                $html = '';
                break;
        }
        return $html;
    }

    public function saveMenu(Request $request)
    {
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $layout = $request->input('layout');
        $widgets = FashionHelper::getWidgetsFlipped();

        $type = $request->input('type');
        $label = $request->input('label');
        $navmenu_id = $request->input('navmenu_id');

        $createData = $updateData = [
        'navmenu_type' => $type,
        'navmenu_label' => $label
        ];

        $createData['navmenu_collection_id'] = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);

        switch ($type) {
            case '1':
                $createData['navmenu_record_id'] = $updateData['navmenu_record_id'] = $request->input('category_id');
                break;
            case '2':
                $createData['navmenu_record_id'] = $updateData['navmenu_record_id'] = $request->input('page_id');
                break;
            case '3':
                $createData['navmenu_url'] = $updateData['navmenu_url'] = $request->input('url');
                $createData['navmenu_target'] = $updateData['navmenu_target'] = $request->input('target');
                break;

            default:
                // code...
                break;
        }

        if (empty($navmenu_id)) {
            $highestOrder = AdminYokartModel::getDisplayOrder('App\Models\NavigationMenu', 'navmenu_display_order', [
                'navmenu_collection_id' => $createData['navmenu_collection_id']
            ]);
            $createData['navmenu_display_order'] = $highestOrder;
            $navmenu = NavigationMenu::create($createData);
            $navmenu_id = $navmenu->navmenu_id;
        } else {
            NavigationMenu::where('navmenu_id', $navmenu_id)->update($updateData);
        }
        return jsonresponse(true, __('NOTI_MENU_SAVED'), ['navmenu_id' => $navmenu_id]);
    }

    public function deleteMenu(Request $request)
    {
        $navmenu_id = $request->input('navmenu_id');
        NavigationMenu::where('navmenu_id', $navmenu_id)->delete();

        $layout = $request->input('layout');
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $widgets = FashionHelper::getWidgetsFlipped();

        $collection = Collection::getCollectionIds($pageid, $cid, $widgets[$layout]);
        $collectionData = NavigationMenu::getMenusByCollectionId($collection[0])->toArray();
        $i = 1;
        if (!empty($collectionData)) {
            foreach ($collectionData as $v) {
                NavigationMenu::where('navmenu_id', $v)->update(['navmenu_display_order' => $i]);
                $i++;
            }
        }

        $highestOrder = $i - 1;
        $request['updateListing'] = 1;
        $html = $this->getCollection($request);
        return jsonresponse(true, __('NOTI_MENU_DELETED'), ['html' => $html, 'highestOrder' => $highestOrder]);
    }

    public function saveSliderSettings(Request $request)
    {
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $layout = $request->input('layout');
        $slide_duration = $request->input('slide_duration');
        $widgets = FashionHelper::getWidgetsFlipped();

        $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);

        CollectionMeta::saveOrUpdate($collection_id, 'BANNER_SLIDER_DURATION', $slide_duration);

        return jsonresponse(true, __('NOTI_SLIDER_SETTINGS_UPDATED'));
    }

    public function saveSlide(Request $request)
    {
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $layout = $request->input('layout');
        $slide_url = $request->input('slide_url');
        $slide_id = $request->input('slide_id');
        $slide_target = $request->input('slide_target');
        $widgets = FashionHelper::getWidgetsFlipped();
        $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);

        $slide_id = Slide::saveOrUpdate($collection_id, $slide_url, $slide_target, $slide_id);

        return jsonresponse(true, __('NOTI_SLIDER_SETTINGS_UPDATED'), ['slide_id' => $slide_id]);
    }

    public function deleteSlide(Request $request)
    {
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $layout = $request->input('layout');
        $slide_id = $request->input('slide_id');
        $widgets = FashionHelper::getWidgetsFlipped();
        $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);

        Slide::deleteSlide($slide_id);
        AttachedFile::where('afile_record_id', $collection_id)
        ->where('afile_record_subid', $slide_id)
        ->where('afile_type', AttachedFile::getConstantVal('bannerSlider'))->delete();

        return jsonresponse(true, __('NOTI_SLIDE_DELETED'));
    }

    public function saveSlideImage(Request $request)
    {
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $slide_id = $request->input('slide_id');
        $slide_type = $request->input('slide_type');
        $layout = $request->input('layout');

        $widgets = FashionHelper::getWidgetsFlipped();
        $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);

        if (empty($slide_id)) {
            $slide_id = Slide::saveOrUpdate($collection_id);
        }
        
        imageUpload($request, $collection_id, 'bannerSlider', $slide_id, AttachedFile::DEVICE_TYPE[$slide_type]);
      
        $bannerSlide = AttachedFile::getAttachedFile(AttachedFile::getConstantVal('bannerSlider'), $collection_id, $slide_id, AttachedFile::DEVICE_TYPE[$slide_type]);
        $url = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/thumb?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
        $originalUrl = !empty($bannerSlide->afile_id) ? url('yokart/image/' . $bannerSlide->afile_id . '/original?t=' . strtotime($bannerSlide->afile_updated_at)) : '';
        return jsonresponse(true, __('NOTI_SLIDE_SAVED'), ['slide_id' => $slide_id, 'url' => $url, 'originalUrl' => $originalUrl]);
    }

    public function deleteSlideImage(Request $request)
    {
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $slide_id = $request->input('slide_id');
        $slide_type = $request->input('slide_type');
        $layout = $request->input('layout');

        $widgets = FashionHelper::getWidgetsFlipped();
        $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout]);
        AttachedFile::deleteFileById(AttachedFile::getConstantVal('bannerSlider'), $collection_id, $slide_id, AttachedFile::DEVICE_TYPE[$slide_type]);
        return jsonresponse(true, __('NOTI_IMAGE_REMOVED'));
    }

    
    public function deleteCollectionImage(Request $request)
    {
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $layout = $request->input('layout');
        $record_id = !empty($request->input('record_id')) ? $request->input('record_id') : 0;

        $widgets = FashionHelper::getWidgetsFlipped();
        $collection_id = Collection::generateCollectionId($pageid, $cid, $widgets[$layout], $record_id);
        AttachedFile::deleteFileById(AttachedFile::getConstantVal($layout), $collection_id);
        return jsonresponse(true, __('NOTI_IMAGE_REMOVED'));
    }

    public function checkIfExists(Request $request)
    {
        $layout = $request->input('layout');
        $record = $request->input('record');
        $cid = $request->input('cid');
        $pageid = self::pageId($request);
        $widgets = FashionHelper::getWidgetsFlipped();
        $exists = false;
        if ($layout == "categoryCollectionLayout1") {
            $count = Collection::where('collection_component_id', $cid)
            ->where('collection_page_id', $pageid)
            ->where('collection_layout', $widgets[$layout])
            ->where('collection_record_id', $record)
            ->count();
            $exists = ($count > 0) ? true : false;
        }
        return jsonresponse(true, null, ['exists' => $exists]);
    }

    private function pageId($request)
    {
        return !empty($request->header('pageid')) ? $request->header('pageid') : '1';
    }
}
