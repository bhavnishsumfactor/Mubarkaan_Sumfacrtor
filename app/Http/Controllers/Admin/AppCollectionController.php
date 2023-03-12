<?php

namespace App\Http\Controllers\Admin;

use App\Models\AppCollection;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\AttachedFile;
use App\Models\BlogPost;
use App\Models\Product;
use App\Models\Page;
use App\Models\Brand;
use App\Models\AppPage;
use App\Models\AppExplore;
use App\Models\Testimonial;
use App\Models\AppCollectionSlider;
use App\Models\Configuration;
use App\Models\AppCollectionToRecord;
use App\Models\AppCollectionSliderLink;
use App\Models\AppCollectionRecordToData;
use App\Models\Language;
use App\Http\Controllers\Admin\AdminYokartController;

class AppCollectionController extends AdminYokartController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageId = $request->input('page-id');
        if ($request->input('records-type') == "false") {
            $records['defaultListing'] = AppCollection::getListing($pageId);
        }
        if ($pageId == 1) {
            $records['appListing'] = AppCollectionToRecord::getHomePageListing($pageId);
        } else {
            $records['appListing'] = AppCollectionToRecord::getContentPageListing($pageId);
        }
        $records['pageInfo'] = AppPage::where('page_id', $pageId)->first();
        return jsonresponse(true, null, $records);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCollections(Request $request)
    {
        $type = $request->input('type');
        $collectionId = $request->input('collection-id');
        $records = [];
        $editRecords = [];
        switch ($type) {
            case 'category-collection1':

                $categories = ProductCategory::getParentList();
                if (!empty($categories) && count($categories) > 0) {
                    $categories =  ProductCategory::buildTree($categories);
                }
                $records['categories'] = $categories;
                if ($collectionId) {
                    $editRecords = AppCollectionToRecord::getCatCollectionById($collectionId);
                }
                $records['editData'] = $editRecords;
                break;
            case 'category-collection2':

                $categories = ProductCategory::getParentList();
                if (!empty($categories) && count($categories) > 0) {
                    $categories =  ProductCategory::buildTree($categories);
                }
                $records['categories'] = $categories;
                if ($collectionId) {
                    $editRecords = AppCollectionToRecord::getCatCollectionById($collectionId);
                }
                $records['editData'] = $editRecords;
                break;
            case 'category-collection3':

                $categories = ProductCategory::getParentList();
                if (!empty($categories) && count($categories) > 0) {
                    $categories =  ProductCategory::buildTree($categories);
                }
                $records['categories'] = $categories;
                if ($collectionId) {
                    $editRecords = AppCollectionToRecord::getCatCollectionById($collectionId);
                }
                $records['editData'] = $editRecords;
                break;
            case 'banner-slider-collection1':

                $linkingTypes = AppCollectionSlider::SLIDER_LINKING_TYPE;
                $records['linkingTypes'] = $linkingTypes;
                if ($collectionId) {
                    $editRecords = AppCollectionSlider::getBannerCollectionById($collectionId);
                }
                $records['editData'] = $editRecords;
                break;
            case 'banner-slider-collection2':

                $linkingTypes = AppCollectionSlider::SLIDER_LINKING_TYPE;
                $records['linkingTypes'] = $linkingTypes;
                if ($collectionId) {
                    $editRecords = AppCollectionSlider::getBannerCollectionById($collectionId);
                }
                $records['editData'] = $editRecords;
                break;
            case 'banner-slider-collection3':

                $linkingTypes = AppCollectionSlider::SLIDER_LINKING_TYPE;
                $records['linkingTypes'] = $linkingTypes;
                if ($collectionId) {
                    $editRecords = AppCollectionSlider::getBannerCollectionById($collectionId);
                }
                $records['editData'] = $editRecords;
                break;
            case 'product-collection1':
                    $editRecords = AppCollectionToRecord::getProductCollectionById($collectionId);
                     $records['editData'] = $editRecords;
                break;
            case 'product-collection2':
                    $editRecords = AppCollectionToRecord::getProductCollectionById($collectionId);
                     $records['editData'] = $editRecords;
                break;
            case 'testimonial-collection1':
                    $editRecords = AppCollectionToRecord::getTestimonialCollectionById($collectionId);
                     $records['editData'] = $editRecords;
                break;
            case 'testimonial-collection2':
                $editRecords = AppCollectionToRecord::getTestimonialCollectionById($collectionId);
                    $records['editData'] = $editRecords;
            break;
            case 'brand-collection1':
                    $editRecords = AppCollectionToRecord::getBrandCollectionById($collectionId);
                     $records['editData'] = $editRecords;
                break;
            case 'brand-collection2':
                $editRecords = AppCollectionToRecord::getBrandCollectionById($collectionId);
                    $records['editData'] = $editRecords;
            break;
            case 'blog-collection1':
                    $editRecords = AppCollectionToRecord::getBlogCollectionById($collectionId);
                    $records['editData'] = $editRecords;
                break;
            case 'blog-collection2':
                $editRecords = AppCollectionToRecord::getBlogCollectionById($collectionId);
                $records['editData'] = $editRecords;
            //    print_r($records);exit;
                break;
            case 'tags':
                $editRecords = AppCollectionRecordToData::getTagsCollectionById($collectionId);
                $records['editData'] = $editRecords;
            break;
            case 'categories':
                $configrations = ['APP_DISPLAY_BUY_TOGETHER',
                'APP_REVIEWS_AUTO_APPROVED',
                 'APP_DISPLAY_SIMILAR_PRODUCTS',
                  'APP_CONTENT_PAGES_ENABLED',
                  'BUSINESS_LATITUDE',
                  'BUSINESS_LONGITUDE',
                  'BUSINESS_ADDRESS',
                  'MOBILE_DEFAULT_LANGUAGE',
                  'FCM_SERVER_KEY'
                ];
                $editRecords['languages'] = Language::getAll();
                $editRecords['config'] = Configuration::getKeyValues($configrations);
                $editRecords['categories'] = AppCollectionToRecord::getFavCategories();

                $records['editData'] = $editRecords;
                break;
        }
        return jsonresponse(true, null, $records);
    }
    public function store(Request $request)
    {
        $type = $request->input('type');
        switch ($type) {
            case 'category-collection1':
                $this->storeCollections($request, AppCollection::CAT_COLLECTION1);
                break;
            case 'category-collection2':
                $this->storeCollections($request, AppCollection::CAT_COLLECTION2);
                break;
            case 'category-collection3':
                $this->storeCollections($request, AppCollection::CAT_COLLECTION3);
                break;
            case 'brand-collection1':
                $this->storeCollections($request, AppCollection::BRAND_COLLECTION1);
                break;
            case 'brand-collection2':
                $this->storeCollections($request, AppCollection::BRAND_COLLECTION2);
                break;
            case 'product-collection1':
                $this->storeCollections($request, AppCollection::PRODUCT_COLLECTION1);
                break;
            case 'product-collection2':
                $this->storeCollections($request, AppCollection::PRODUCT_COLLECTION2);
                break;
            case 'testimonial-collection1':
                $this->storeCollections($request, AppCollection::TESTIMONIAL_COLLECTION1);
                break;
            case 'testimonial-collection2':
                $this->storeCollections($request, AppCollection::TESTIMONIAL_COLLECTION2);
                break;
            case 'blog-collection1':
                $this->storeCollections($request, AppCollection::BLOG_COLLECTION1);
                break;
            case 'blog-collection2':
                $this->storeCollections($request, AppCollection::BLOG_COLLECTION2);
                break;
            case 'banner-slider-collection1':
                $this->storeBannerSliderCollection($request, AppCollection::BANNER_SLIDER1);
                break;
            case 'banner-slider-collection2':
                $this->storeBannerSliderCollection($request, AppCollection::BANNER_SLIDER2);
                break;
            case 'banner-slider-collection3':
                $this->storeBannerSliderCollection($request, AppCollection::BANNER_SLIDER3);
                break;
            case 'recent-bought-collection1':
                $this->storeRecentBoughtCollection($request, AppCollection::RECENT_BOUGHT_COLLECTION1);
                break;
            case 'tags':
                $this->storeHTMLTagsCollections($request);
                break;
            case 'categories':
                $this->storeFavCategories($request);
                break;
            case 'contentpages':
                $this->storeContentPages($request);
                break;
            case 'appExplore':
                $this->storeAppExplore($request);
                break;
        }
        return jsonresponse(true, null);
    }
    private function storeContentPages($request)
    {
        $pageName = $request->input('title');
        $pageSlug = slugify($pageName);
        $pageId = AppPage::create([
            'page_title' => $pageName,
            'page_slug' => $pageSlug,
            'page_type' => AppPage::PAGE_TYPE_CMS,
        ])->page_id;
        $lastOrder = 1;
        if ($record = AppCollection::orderBy('ac_id', 'Desc')->first()) {
            $lastOrder = $record->ac_id;
        }
        foreach (AppCollection::TAGS_TYPES as $key => $tags) {
            AppCollection::create([
                'ac_page_id' => $pageId,
                'ac_type' => $key,
                'ac_display_order' => $lastOrder + 1,
            ]);
            $lastOrder++;
        }
        return jsonresponse(true, null);
    }
    private function storeAppExplore($request)
    {
        $pageId = $request->input('page_id');
        $aeType = $request->input('page_type');
        $aeTtile = $request->input('title');
        $pageId = AppExplore::create([
            'ae_app_page_id' => $pageId,
            'ae_type' => $aeType,
            'ae_title' => $aeTtile,
        ])->ae_id;
        return jsonresponse(true, "Page added successfully");
    }
    private function storeFavCategories($request)
    {
        $record = AppCollectionToRecord::where('actr_type', AppCollection::FAV_CATEGORIES)->first();
        $tags = json_decode($request->input('tags'), true);

        if (empty($record)) {
            $order = 1;
            if ($record = AppCollectionToRecord::orderBy('actr_id', 'Desc')->first()) {
                $order = $record->actr_id + 1;
            }
            $insertedId =  AppCollectionToRecord::create([
                "actr_ac_id" => 0,
                "actr_type" => AppCollection::FAV_CATEGORIES,
                "actr_display_order" => $order,
                "actr_updated_on" => now(),
            ])->actr_id;
        } else {
            $insertedId = $record->actr_id;
        }
        AppCollectionRecordToData::where('acrd_actr_id', $insertedId)->delete();
        foreach ($tags as $tag) {
            $data = [
                "acrd_actr_id" => $insertedId,
                "acrd_record_id" => $tag['id'],
                "acrd_subrecord_id" => 0,
                ];
            AppCollectionRecordToData::create($data);
        }
    }
    private function storeHTMLTagsCollections($request)
    {
        $pageId = $request->input('page-id');
        $acId = $request->input('ac-id');
        $insertedId = $request->input('actr-id');
        $recordId = $request->input('record_id');
        $acrdId = $request->input('acrd_id');
        $type = $request->input('collectionType');
        $imgId = $request->input('img_id');
        $collection = $request->input('collection');
        $ulTags = json_decode($request->input('ui_tags'), true);
       
        $order = 1;
        if ($record = AppCollectionToRecord::orderBy('actr_id', 'Desc')->first()) {
            $order = $record->actr_id + 1;
        }
        if ($insertedId == 0) {
            $insertedId =  AppCollectionToRecord::create([
                "actr_ac_id" => $acId,
                "actr_type" => $type,
                "actr_display_order" => $order,
                "actr_updated_on" => now(),
            ])->actr_id;
        } else {
            AppCollectionToRecord::where('actr_id', $insertedId)->update([ "actr_updated_on" => now()]);
        }
        $data = [
            "acrd_actr_id" => $insertedId,
            "acrd_record_id" => 0,
            "acrd_subrecord_id" => 0,
            ];
        if ($type == AppCollection::UL_TAG) {
            foreach ($ulTags as $ulTag) {
                $data["acrd_description"] = $ulTag['name'];
                if (AppCollectionRecordToData::where('acrd_id', $ulTag['acrd_id'])->exists()) {
                    AppCollectionRecordToData::where('acrd_id', $ulTag['acrd_id'])->update($data);
                } else {
                    AppCollectionRecordToData::create($data);
                }
            }
        } else {
            $data["acrd_description"] = $collection;
            if (AppCollectionRecordToData::where('acrd_id', $acrdId)->exists()) {
                AppCollectionRecordToData::where('acrd_id', $acrdId)->update($data);
                $recordId = $acrdId;
            } else {
                $recordId = AppCollectionRecordToData::create($data)->acrd_id;
            }

            if (isset($imgId) && !empty($imgId)) {
                AttachedFile::where('afile_id', $imgId)->update([
                    'afile_record_id' => $recordId,
                    'afile_record_subid' => 0,
                    ]);
            }
        }
    }
    private function storeBannerSliderCollection($request, $type)
    {
        $pageId = $request->input('page-id');
        $acId = $request->input('ac-id');
        $insertedId = $request->input('actr-id');
        $recordId = $request->input('record_id');
        $duration = $request->input('duration');
        $title = $request->input('title');
        $collections = json_decode($request->input('collection'), true);
        if ($insertedId == 0) {
            $insertedId =  AppCollectionToRecord::create([
                "actr_ac_id" => $acId,
                "actr_type" => $type,
                "actr_slide_duration" => $duration,
                "actr_title" => $title,
                "actr_display_order" => AppCollectionToRecord::count() + 1,
                "actr_updated_on" => now(),
            ])->actr_id;
        } else {
            AppCollectionToRecord::where('actr_id', $insertedId)->update([
                "actr_slide_duration" => $duration,
                "actr_title" => $title,
                "actr_updated_on" => now()
                ]);
        }

        foreach ($collections as $collection) {
            $selectedLinks = $collection['selectedLinks'];
         
            $data = [
                "acs_actr_id" => $insertedId,
                "acs_type" => $collection['linkType'],
             ];
            if (AppCollectionSlider::where('acs_id', $collection['acs_id'])->exists()) {
                AppCollectionSlider::where('acs_id', $collection['acs_id'])->update($data);
                $recordId = $collection['acs_id'];
            } else {
                $data["acrd_display_order"] = AppCollectionSlider::count() + 1;
                $recordId = AppCollectionSlider::create($data)->acs_id;
            }
            if (isset($collection['imgid']) && !empty($collection['imgid'])) {
                AttachedFile::where('afile_id', $collection['imgid'])->update([
                    'afile_record_id' => $recordId,
                    'afile_record_subid' => 0,
                    ]);
            }
            if ($collection['linkType'] == 5) {
                $selectedLinks[] = ['record_id' => $collection['searchKeyword']];
            }
        
            if (count($selectedLinks) > 0) {
                AppCollectionSliderLink::where('acsl_acs_id', $recordId)->delete();
                foreach ($selectedLinks as $selectedLink) {
                    AppCollectionSliderLink::create([
                        'acsl_acs_id' => $recordId,
                        'acsl_value' => $selectedLink['record_id']
                    ]);
                }
            }
        }
    }
    private function storeRecentBoughtCollection($request, $type)
    {
        $pageId = $request->input('page-id');
        $acId = $request->input('ac-id');
        $insertedId = $request->input('actr-id');
        AppCollectionToRecord::create([
                "actr_ac_id" => $acId,
                "actr_type" => $type,
                "actr_display_order" => AppCollectionToRecord::count() + 1,
                "actr_updated_on" => now(),
            ]);
    }
    private function storeCollections($request, $type)
    {
        $pageId = $request->input('page-id');
        $acId = $request->input('ac-id');
        $insertedId = $request->input('actr-id');
        $recordId = $request->input('record_id');
        $title = $request->input('title');
        $collections = json_decode($request->input('collection'), true);
      
        if ($insertedId == 0) {
            $insertedId =  AppCollectionToRecord::create([
                "actr_ac_id" => $acId,
                "actr_type" => $type,
                "actr_title" => $title,
                "actr_display_order" => AppCollectionToRecord::count() + 1,
                "actr_updated_on" => now(),
            ])->actr_id;
        } else {
            AppCollectionToRecord::where('actr_id', $insertedId)->update(["actr_title" => $title, "actr_updated_on" => now()]);
        }
       
        
        foreach ($collections as $collection) {
            $data = [
                "acrd_actr_id" => $insertedId,
                "acrd_record_id" => $collection['record_id'],
                "acrd_subrecord_id" => 0,
                "acrd_display_order" => $collection['order'],
             ];
            if (AppCollectionRecordToData::where('acrd_id', $collection['acrd_id'])->exists()) {
                AppCollectionRecordToData::where('acrd_id', $collection['acrd_id'])->update($data);
                $recordId = $collection['acrd_id'];
            } else {
                $recordId = AppCollectionRecordToData::create($data)->acrd_id;
            }
            
            if (isset($collection['imgid']) && !empty($collection['imgid'])) {
                AttachedFile::where('afile_id', $collection['imgid'])->update([
                    'afile_record_id' => $recordId,
                    'afile_record_subid' => 0,
                    ]);
            }
        }
    }
    
    public function updateSorting(Request $request)
    {
        $records = json_decode($request->input('sorting'), true);
        foreach ($records as $record) {
            AppCollectionToRecord::where('actr_id', $record['id'])->update(["actr_display_order" => $record['order']]);
        }
        return jsonresponse(true, null);
    }
    public function uploadImages(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');
        $subId = $request->input('subid');
        if ($request->hasFile('actualImage')) {
            $fileName = $request->file('actualImage')->getClientOriginalName();
            if (AttachedFile::where([
                'afile_type' => AttachedFile::SECTIONS[$type],
                'afile_record_id' => $id,
                'afile_record_subid' => $subId,
                'afile_name' => $fileName,
            ])->count() > 0) {
                return jsonresponse(true);
            }
        }
       
        $record = imageUpload($request, $id, $type, $subId);
        return jsonresponse(true, null, $record);
    }


    public function deleteImage(Request $request)
    {
        $imageId = $request->input('img-id');
        AttachedFile::deleteFileByAfileId($imageId);
        return jsonresponse(true, null);
    }

    public function deleteCollection(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');

        AppCollectionToRecord::where('actr_id', $id)->delete();
        $imageIds = [];
        if (!empty($type) && $type == 'banner') {
            $imageIds = AppCollectionSlider::where('acs_actr_id', $id)->pluck('acs_id')->toArray();
            AppCollectionSlider::where('acs_actr_id', $id)->delete();
            AppCollectionSliderLink::whereIn('acsl_acs_id', $imageIds)->delete();
            $type = 'appBannerSliderCollection';
        } else {
            $imageIds = AppCollectionRecordToData::where('acrd_actr_id', $id)->pluck('acrd_id')->toArray();
            AppCollectionRecordToData::where('acrd_actr_id', $id)->delete();
        }
        if (!empty($type) &&  count($imageIds) > 0) {
            AttachedFile::deleteBulkFilesByIds(AttachedFile::getConstantVal($type), $imageIds);
        }
        return jsonresponse(true, null);
    }
    public function deleteRecord(Request $request)
    {
        $imgId = $request->input('img_id');
        $recordId = $request->input('record_id');
        $type = $request->input('type');
        if (!empty($type) && $type == 'banner') {
            AppCollectionSlider::where('acs_id', $recordId)->delete();
            AppCollectionSliderLink::where('acsl_acs_id', $recordId)->delete();
        } else if (!empty($type) && $type == 'content-pages') {
             $this->deletContentPage($recordId);
        } else {
            AppCollectionRecordToData::where('acrd_id', $recordId)->delete();
        }
        if ($imgId != 0) {
            AttachedFile::deleteFileByAfileId($imgId);
        }
        return jsonresponse(true, null);
    }
   
    private function deletContentPage($recordId)
    {
        AppPage::where('page_id', $recordId)->delete();
        $appRecords =  AppCollection::where('ac_page_id', $recordId)->get()->pluck('ac_id')->toArray();
        AppCollection::where('ac_page_id', $recordId)->delete();
        $appColRecords =  AppCollectionToRecord::whereIn('actr_ac_id', $appRecords)->get()->pluck('actr_id')->toArray();
        if (count($appColRecords) > 0) {
            AppCollectionToRecord::whereIn('actr_ac_id', $appRecords)->delete();
            AppCollectionRecordToData::whereIn('acrd_actr_id', $appColRecords)->delete();
        }
    }
    public function searchRecords(Request $request)
    {
        $keyword = $request->input('search');
       
        $type = strtolower($request->input('type'));
       
        $recrods = [];
        switch ($type) {
            case 'brand':
                $recrods = Brand::getBrandsByName($keyword, []);
                break;
            case 'product':
                $recrods = Product::getProductsByName($keyword, []);
                break;
            case 'category':
                $recrods = ProductCategory::getCategoriesByName($keyword, []);
                break;
            case 'cms':
                $fields = ['page_name as label', 'page_id as value'];
                $recrods = Page::getPages($fields, ['page_publish' => config('constants.YES')], $keyword);
             
                break;
            case 'blog':
                $recrods = BlogPost::getPostsByName($keyword, []);
                break;
            case 'testimonial':
                $recrods = Testimonial::getRecordsByName($keyword, []);
                break;
        }
      
        return jsonresponse(true, null, $recrods);
    }
}
