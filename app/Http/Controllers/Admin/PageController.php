<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\ProductCategory;
use App\Models\Admin\AdminRole;
use App\Models\Collection;
use App\Helpers\ThemeHelper;
use App\Models\UrlRewrite;
use App\Models\Configuration;
use App\Http\Requests\PageRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use Arr;
use App\Models\Theme;
use App\Models\AttachedFile;
use Artisan;
use App\Models\CollectionMeta;
use App\Models\NavigationMenu;
use App\Models\Slide;
use Illuminate\Support\Facades\File;
use DB;

class PageController extends AdminYokartController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::PAGES)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $records['pages'] = Page::getAllPages($request->all());
        $records['types'] = Page::PAGE_TYPE;

        $status = (count($records['pages']) > 0) ? true : false;

        return jsonresponse($status, '', $records);
    }

    public function updateStatus(Request $request, $page_id)
    {
        if (!canWrite(AdminRole::PAGES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $publishStatus = (!empty($request->input('status'))) ? 1 : 0;
        Page::where('page_id', $page_id)->update(['page_publish' => $publishStatus]);
        $message = (!empty($request->input('status'))) ? __('NOTI_PAGE_PUBLISHED') : __('NOTI_PAGE_UNPUBLISHED');
        return jsonresponse(true, $message);
    }

    public function destroy($id)
    {
        if (!canWrite(AdminRole::PAGES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        Page::where('page_id', $id)->delete();
        UrlRewrite::removeUrl('pages', $id);
        return jsonresponse(true, __('NOTI_PAGE_DELETED'));
    }

    public function duplicatePage(PageRequest $request)
    {
        if (!canWrite(AdminRole::PAGES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $page = Page::find($request->input('page_id'));
        $duplicatePage = $page->replicate();
        $duplicatePage->page_name  = $request->input('page_name');
        $duplicatePage->page_default  = 0;
        $duplicatePage->save();
        UrlRewrite::saveUrl('pages', $duplicatePage->page_id);
        return jsonresponse(true, __('NOTI_PAGE_DUPLICATED'));
    }

    public function resetPage(Request $request)
    {
        if (!canWrite(AdminRole::PAGES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $pageId = $request->input('page_id');
        if ($this->resetPageData($pageId) === false) {
            return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'), []);
        }

        if ($pageId == 1) {
            $configurations = getConfigValues([
                'HEADER_HTML_DEFAULT',
                'FOOTER_HTML_DEFAULT'
            ]);
            $configurations['HEADER_HTML_DEFAULT'] = str_replace("http:\/\/localhost\/singlevendor", trim(json_encode(url('')), '"'), $configurations['HEADER_HTML_DEFAULT']);
            
            if (isset($configurations['HEADER_HTML_DEFAULT']) && isset($configurations['FOOTER_HTML_DEFAULT'])) {
                Configuration::bulkUpdate([
                    'HEADER_HTML' => $configurations['HEADER_HTML_DEFAULT'],
                    'FOOTER_HTML' => $configurations['FOOTER_HTML_DEFAULT']
                ]);
            }
            \Cache::forget('header-section');
            \Cache::forget('footer-section');
        }
        \Artisan::call("cache:clear");
        return jsonresponse(true, __('NOTI_PAGE_RESET'));
    }

    private function resetPageData($pageId)
    {
        DB::beginTransaction();
        try {
            $collectionIds = Collection::where('collection_page_id', $pageId)->get()->pluck('collection_id')->toArray();
            Page::where('page_id', $pageId)->delete();
            Collection::whereIn('collection_id', $collectionIds)->delete();
            CollectionMeta::whereIn('cmeta_collection_id', $collectionIds)->delete();
            Slide::whereIn('slide_collection_id', $collectionIds)->delete();
            switch ($pageId) {
                case 1: /*home */
                    $afileIds = [2233, 2234, 2235, 2236, 2379, 2380, 2381, 2382, 2383, 2384, 2385, 2386, 2387, 2243, 2279, 2237, 2272, 2241, 2242, 2273, 2274, 2275, 2276, 2277, 2278, 2362, 2363, 2364, 2390, 2391, 2355, 2357, 2358, 2359, 2377, 2388, 2389, 2370, 2371, 2373, 2374, 2375, 2392, 2366, 2367, 2368, 2365];
                    AttachedFile::whereIn('afile_id', $afileIds)->delete();
                    NavigationMenu::whereIn('navmenu_collection_id', $collectionIds)->delete();
                    Artisan::call('db:seed', ['--class' => 'HomePageTableSeeder']);
                    File::copyDirectory(base_path('public/dummydata/homepage/images'), storage_path('app/public'));
                    break;
                case 2: /*about */
                    $afileIds = [2280, 2475, 2467, 2468, 2469, 2470, 2463, 2464, 2465, 2466];
                    AttachedFile::whereIn('afile_id', $afileIds)->delete();
                    Artisan::call('db:seed', ['--class' => 'AboutPageTableSeeder']);
                    File::copyDirectory(base_path('public/dummydata/aboutpage/images'), storage_path('app/public'));
                    break;
                case 3: /*terms */
                    Artisan::call('db:seed', ['--class' => 'TermsPageTableSeeder']);
                    break;
                case 4: /*contactus */
                    Artisan::call('db:seed', ['--class' => 'ContactPageTableSeeder']);
                    break;
                case 5: /*privacy */
                    Artisan::call('db:seed', ['--class' => 'PrivacyPageTableSeeder']);
                    break;
                
                default:
                    # code...
                    break;
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function updateSkillLevel(Request $request, $id)
    {
        if (!canWrite(AdminRole::PAGES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $skill_level = $request['skill_level'] + 1;
        if ($skill_level > 3) {
            $skill_level = 1;
        }
        Configuration::saveValue("CMS_SKILL_LEVEL", $skill_level);
        
        return redirect('admin/pages/' . $id . '/edit');
    }

    public function edit(Request $request, $pageId)
    {
        if (!canWrite(AdminRole::PAGES)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        $configurations = getConfigValues([
            'FRONTEND_LOGO_RATIO',
            'CMS_SKILL_LEVEL'
        ]);

        $theme = Theme::getActiveTheme();
        $themeSlug = !empty($theme->theme_slug) ? $theme->theme_slug : config('constants.THEME');
        config(['theme' => $themeSlug]);
        $themeClass = 'App\Helpers\\' . ucwords($themeSlug) . 'Helper';
        $excludeIds = [];
        if ($pageId == 1) {
            $excludeIds = [19, 101];
        }
        $widgets = $themeClass::get($excludeIds);

        $themeWidgetNames = $themeClass::getWidgetNames();
        if ($pageId == 1) {
            $themeWidgetNames = Arr::except($themeWidgetNames, $excludeIds);
        }
        $themeWidgetNames = implode("','", $themeWidgetNames);

        $generalWidgetNames = ThemeHelper::getWidgetNames();
        $generalWidgetNames = implode("','", $generalWidgetNames);

        $cmsSkillLevel = $configurations['CMS_SKILL_LEVEL'];
        $viewPanelsToRemove = '';
        $cmsSkillLevelTitle = 'Expert';
        if ($cmsSkillLevel == 1) {
            $viewPanelsToRemove = "open-sm','open-tm', 'open-layers";
            $cmsSkillLevelTitle = 'Beginner';
        } elseif ($cmsSkillLevel == 2) {
            $viewPanelsToRemove = "open-tm','open-layers";
            $cmsSkillLevelTitle = 'Intermediate';
        }
        

        $flippedWidgets = $themeClass::getWidgetsFlipped();

        $productsHtml = [];
        $productCollections = Collection::select('collection_layout', 'collection_component_id')->where('collection_page_id', $pageId)
           ->whereIn('collection_layout', [2, 3, 4])
           ->groupBy('collections.collection_component_id')
           ->get()->toArray();
        if (!empty($productCollections)) {
            foreach ($productCollections as $k => $v) {
                $cid = $v['collection_component_id'];
                if ($v['collection_layout'] == $flippedWidgets['productCollectionLayout1']) {
                    $productsHtml[$cid] = $themeClass::productCollectionLayout1Content($pageId, $cid, '.partials', false, true);
                } elseif ($v['collection_layout'] == $flippedWidgets['productCollectionLayout2']) {
                    $productsHtml[$cid] = $themeClass::productCollectionLayout2Content($pageId, $cid, '.partials', false, true);
                } elseif ($v['collection_layout'] == $flippedWidgets['categoryCollectionLayout1']) {
                    $productsHtml[$cid] = $themeClass::categoryCollectionLayout1Content($pageId, $cid, false, true);
                }
            }
        }
        
        /* categories and pages data for dropdown in navigation menu widget */
        $categories = ProductCategory::select('cat_id as id', 'cat_name as title', 'cat_id', 'cat_name', 'cat_parent')
        ->where('cat_publish', 1)->oldest('cat_display_order')->get()->toArray();
        if (!empty($categories) && count($categories) > 0) {
            $categories =  ProductCategory::buildTree($categories, 0, 0, 'subs');
        }
        $fields = ['page_name as title', 'page_id as id'];
        $pages = Page::getPages($fields, ['page_publish' => config('constants.YES')])->toArray();

        //Logo
        $logo = AttachedFile::getFileUrl('frontendLogo', 0, 0, 'thumb');
        if (empty($logo)) {
            $logo = noImage("160x40.png");
        }
        $pageLoad = 1;
        $logoHtml = view('themes.' . config('theme') . '.widgets.logo', compact('logo', 'pageLoad'))->with('ratio', $configurations['FRONTEND_LOGO_RATIO'])->render();
        
        return view('themes.' . $themeSlug . '.pageBuilder', compact('logoHtml', 'widgets', 'themeWidgetNames', 'generalWidgetNames', 'viewPanelsToRemove', 'cmsSkillLevel', 'cmsSkillLevelTitle', 'productsHtml', 'categories', 'pages'))
        ->with('page_id', $pageId);
    }
}
