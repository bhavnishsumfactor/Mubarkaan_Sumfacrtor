<?php

namespace App\Models;

use App\Models\YokartModel;
use DB;

class Collection extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'collection_id';
    protected $fillable = ['collection_component_id', 'collection_page_id', 'collection_layout', 'collection_record_id',
    'collection_record_subid', 'collection_display_order'];

    public static function recordExists($pageid, $cid, $layout, $recordId = 0, $recordSubId = 0)
    {
        return Collection::where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_layout', $layout)
        ->where('collection_record_id', $recordId)
        ->where('collection_record_subid', $recordSubId)
        ->count();
    }
    public function attachedFile()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'collection_id');
    }
   
   
    public static function getRecord($pageid, $cid, $layout, $recordId = null, $recordSubId = null, $displayOrder = null)
    {
        $query = Collection::select('collection_id', 'collection_record_id', 'collection_display_order')
        ->where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_layout', $layout);
        if ($recordId != null) {
            $query->where('collection_record_id', $recordId);
        }
        if ($recordSubId != null) {
            $query->where('collection_record_subid', $recordSubId);
        }
        if ($displayOrder != null) {
            $query->where('collection_display_order', $displayOrder);
        }
        return $query->first();
    }

    
    public static function getRecords($pageid, $cid, $layout, $recordId = null, $recordSubId = null)
    {
        $query = Collection::where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_layout', $layout);
        if ($recordId != null) {
            $query->where('collection_record_id', $recordId);
        }
        if ($recordSubId != null) {
            $query->where('collection_record_subid', $recordSubId);
        }
        return $query->oldest('collection_display_order')->pluck('collection_id');
    }

    public static function getRecordIds($pageid, $cid, $layout)
    {
        return Collection::where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_layout', $layout)
        ->where('collection_record_subid', 0)
        ->oldest('collection_display_order')
        ->pluck('collection_record_id', 'collection_display_order')->toArray();
    }

    public static function getRecordIdsByCid($pageid, $cid)
    {
        return Collection::where('collection_component_id', $cid)
        ->oldest('collection_display_order')
        ->pluck('collection_record_id')->toArray();
    }
   

    public static function getGroupedRecordIds($pageid, $cid, $layout, $recordId = null)
    {
        if (!empty($recordId)) {
            return Collection::where('collection_component_id', $cid)
            ->where('collection_page_id', $pageid)
            ->where('collection_layout', $layout)
            ->where('collection_record_id', $recordId)
            ->pluck('collection_record_subid', 'collection_display_order')->toArray();
        }
        return Collection::where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_layout', $layout)->groupBy('collection_record_id')
        ->pluck('collection_record_id', 'collection_display_order')->toArray();
    }

    public static function getRecordSubIds($pageid, $cid, $layout, $recordIds)
    {
        return Collection::where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_layout', $layout)
        ->whereIn('collection_record_id', $recordIds)
        ->orderBy('collection_display_order')
        ->pluck('collection_record_subid')->toArray();
    }

    public static function deleteIfExists($pageid, $cid, $layout, $recordId = 0, $recordSubId = 0)
    {
        $query = Collection::where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_layout', $layout)
        ->where('collection_record_id', $recordId);
        if ($recordSubId != 0) {
            $query->where('collection_record_subid', $recordSubId);
        }
        $query->delete();
    }

    public static function getCollectionsInHierarchy($pageid, $cid, $layout = null, $categoryId = null, $published = true)
    {
        $fields =  'prod_id, cat_id, cat_name, prod_name, prod_price, pov_default, coalesce(`pov_quantity`, `prod_stock`) as totalstock, collection_id, collection_display_order,
        prod_stock_out_sellable, prod_min_order_qty, pov_code,
         prod_max_order_qty,' . Product::getPrice();
         
        if (!empty($categoryId)) {
            $collections = $filters = [];
            $query = Product::getListingObj($filters, $fields, true, [], $published);
            $query->joinProductCollection();
            $query->addCondition($pageid, 'collection_page_id');
            $query->addCondition($cid, 'collection_component_id');
            $query->addCondition($categoryId, 'collection_record_id');
            $query->addGroupByCondition('prod_id');
            $query->orderBy('collection_display_order', 'ASC');
            $collections['products'] = $query->getRecords();

            $recordIds = Collection::getGroupedRecordIds($pageid, $cid, $layout, $categoryId);
            $collections['recordIds'] = array_flip($recordIds);

            return $collections;
        }

        /*$collections = Collection::select('collection_record_id as cat_id', 'pc.cat_name')
        ->where('collection_page_id', $pageid)
        ->where('collection_component_id', $cid)->groupBy('collection_record_id', 'pc.cat_name')
        ->join('product_categories as pc', 'pc.cat_id', 'collection_record_id')
        ->oldest('collection_id')
        ->get();*/

        $filters = [];
        $query = Product::getListingObj($filters, $fields, true, [], $published);
        $query->joinProductCollection();
        $query->addCondition($pageid, 'collection_page_id');
        $query->addCondition($cid, 'collection_component_id');
        $query->addGroupByCondition('prod_id');
        $query->orderBy('collection_id', 'ASC');
        //    $query->orderBy('collection_display_order', 'ASC');
        $collections = $query->getRecords();
    
       
        /*   if (!empty($collections)) {
               foreach ($collections as $k => $category) {*/
                
        /*if ($layout != null) {
            $recordIds = Collection::getGroupedRecordIds($pageid, $cid, $layout, $category->cat_id);
            $collections[$k]->recordIds = array_flip($recordIds);
        }*/
        /*    }
        }*/
        
        return $collections;
    }

    public static function getHighestDisplayOrder($pageid, $cid, $layout, $recordId = 0, $recordSubId = 0)
    {
        return Collection::where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_layout', $layout)
        ->where('collection_record_id', $recordId)
        ->where('collection_record_subid', $recordSubId)
        ->pluck('collection_display_order')->toArray();
    }

    public static function getCollectionIds($pageid, $cid, $layout, $recordId = 0, $recordSubId = 0)
    {
        return Collection::where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_layout', $layout)
        ->where('collection_record_id', $recordId)
        ->where('collection_record_subid', $recordSubId)
        ->pluck('collection_id')->toArray();
    }
    
    public static function generateCollectionId($pageId, $cId, $layout, $recordId = 0, $recordSubId = 0, $displayOrder = 0)
    {
        $displayOrder = !empty($displayOrder) && $displayOrder != 'NaN' ? $displayOrder : 1;
        $collection = Collection::getRecord($pageId, $cId, $layout, $recordId, $recordSubId);
      
        if (!empty($collection)) {
            $collection_id = $collection->collection_id;
        } else {
            $collection = Collection::create([
                'collection_page_id' => $pageId,
                'collection_component_id' => $cId,
                'collection_layout' => $layout,
                'collection_record_id' => $recordId,
                'collection_record_subid' => $recordSubId,
                'collection_display_order' => $displayOrder
                ]);
            $collection_id = $collection->collection_id;
        }
        return $collection_id;
    }

    public static function getProductCollections($searchTerm)
    {
        return Collection::join("collection_labels as cl", 'cl.collection_component_id', 'collections.collection_component_id')
                  ->where('collection_page_id', 1)
                  ->where('cl.collection_title', 'like', '%'. $searchTerm. '%')
                  ->whereIn('collection_layout', [2, 4])
                  ->groupBy('collections.collection_component_id')
                  ->get()->toArray();
    }

    public static function getProductCollectionData($productId)
    {
        return Collection::join("collection_labels as cl", 'cl.collection_component_id', 'collections.collection_component_id')
                  ->select('collection_id', 'cl.collection_title as collection_title', 'collections.collection_component_id', 'collection_layout')
                  ->where('collection_page_id', 1)
                  ->where('collection_record_id', $productId)
                  ->whereIn('collection_layout', [2, 4])
                  ->get()->toArray();
    }

    public static function productCollectionExists($collectionId, $productId)
    {
        $collectionComponentId = Collection::where('collection_id', $collectionId)->select('collection_component_id')->first();
        if (!empty($collectionComponentId->collection_component_id)) {
            return Collection::where('collection_page_id', 1)
                      ->where('collection_component_id', $collectionComponentId->collection_component_id)
                      ->where('collection_record_id', $productId)
                      ->whereIn('collection_layout', [2, 4])
                      ->exists();
        }
        return true;
    }

    public static function productCollectionDelete($productId)
    {
        DB::beginTransaction();
        try {
            Collection::where('collection_page_id', 1)
                      ->where('collection_record_id', $productId)
                      ->whereIn('collection_layout', [2, 4])
                      ->delete();
            DB::commit();
        } catch (\Exception $e) {
            return false;
            DB::rollBack();
        }
        return true;
    }
}
