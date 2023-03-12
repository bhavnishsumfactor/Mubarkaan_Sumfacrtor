<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Helpers\ProductSearchHelper;
use DB;
use Carbon\Carbon;
use App\Models\AttachedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends YokartModel
{
    use HasFactory;

    protected $primaryKey = 'cat_id';

    const CREATED_AT = 'cat_created_at';
    const UPDATED_AT = 'cat_updated_at';

    protected $fillable = [
        'cat_id', 'cat_name', 'cat_parent', 'cat_publish', 'cat_display_order', 'cat_created_by_id', 'cat_tax_code', 'cat_updated_by_id', 'cat_created_at', 'cat_updated_at'
    ];
    public static function cacheKey()
    {
        $item = new ProductCategory;
        return sprintf(
            "%s/%s-%s",
            $item->getTable(),
            $item->getKey(),
            Carbon::parse($item->max('cat_updated_at'))->timestamp
        );
    }
    public function childs()
    {
        return $this->hasMany('App\Models\ProductCategory', 'cat_parent', 'cat_id')->with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')->oldest('cat_display_order');
    }
    public static function getSubcategories($catIds)
    {
        $childCategories = ProductCategory::getCategoriesByParentId($catIds)->pluck('cat_id')->toArray();
        if(is_array($catIds)){
            array_unshift($childCategories, ...$catIds);
        }else{
            array_unshift($childCategories, $catIds);
        }
        
        return $childCategories;
    }
    public function uploadImages()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'cat_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['productCategoryBanner']);
    }
  
    public function categories()
    {
        return $this->hasMany('App\Models\ProductCategory', 'cat_id', 'collection_record_id');
    }
    public function products()
    {
        return $this->hasOne('App\Models\ProductToCategory', 'ptc_cat_id', 'cat_id');
    }
    public static function getCategoryCollections($pageid, $cid, $type)
    {
        return ProductCategory::select('cat_id', 'cat_name', 'afile_id', 'collection_id', 'afile_updated_at')
        ->with('urlRewrite')->join('collections', 'cat_id', 'collection_record_id')
        ->leftJoin('attached_files AS af', function ($join) use ($type) {
            $join->on('collection_id', 'af.afile_record_id');
            $join->where('af.afile_type', $type);
        })->where('collection_component_id', $cid)
        ->where('collection_page_id', $pageid)
        ->where('collection_record_subid', 0)
        ->oldest('collection_display_order')->get();
    }
    public static function getCategories()
    {
        return Cache::remember(static::cacheKey(), 28800, function () {
            return ProductCategory::with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')->select(
                'cat_id',
                'cat_name',
                'cat_tax_code',
                'cat_parent',
                'cat_publish',
                'cat_display_order',
                'cat_updated_at',
                'af.afile_id',
                'cat_created_by_id',
                'cat_updated_by_id',
                'cat_created_at',
                'cat_updated_at',
            )->leftJoin('attached_files AS af', function ($join) {
                $join->on('cat_id', '=', 'af.afile_record_id');
                $join->where('af.afile_type', AttachedFile::getConstantVal('productCategoryBanner'));
            })->with(['createdAt', 'updatedAt'])->withCount('productCounts')->oldest('cat_display_order')->get()->toArray();
        });
    }
    public static function getParentCategories()
    {
        return Cache::remember('parentCat-' . static::cacheKey(), 28800, function () {
            return ProductCategory::with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')->select(
                'cat_id',
                'cat_name',
                'cat_display_order',
                'cat_parent'
            )->where('cat_parent', 0)->where('cat_publish', config('constants.YES'))->oldest('cat_display_order')->get();
        });
    }
    public function urlRewrite()
    {
        return $this->belongsTo('App\Models\UrlRewrite', 'cat_id', 'urlrewrite_record_id')->where('urlrewrite_type', UrlRewrite::CATEGORY_TYPE);
    }
    public static function getAllCategories($request, $withImages = false)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = ProductCategory::select('cat_id as record_id', 'cat_name as record_title', 'urlrewrite_id', 'urlrewrite_original', 'urlrewrite_custom')
            ->leftJoin('url_rewrites', function ($sql) {
                $sql->on('urlrewrite_record_id', 'cat_id')->where('urlrewrite_type', UrlRewrite::CATEGORY_TYPE);
            });
        if ($withImages) {
            $query->has('uploadImages', '>', 0);
        }
        if (!empty($request['search'])) {
            $query->where('cat_name', 'like', '%' . $request['search'] . '%');
        }
        return $query->latest('cat_id')->paginate((int) $per_page);
    }

    public static function getParentList($catId = null)
    {
        $query = ProductCategory::select('cat_name as label', 'cat_id', 'cat_id as id', 'cat_parent');
        if ($catId != null) {
            $query->where('cat_id', '!=', $catId);
            $childs = ProductCategory::getByParent($catId)->pluck('cat_id');
            $query->whereNotIn('cat_id', $childs);
        }
        return $query->oldest('cat_display_order')->with('createdAt', 'updatedAt')->get()->toArray();
    }

    public static function getActiveCategories($catId = null)
    {
        $query = ProductCategory::where('cat_publish', 1);
        if ($catId != null) {
            $query->where('cat_id', '!=', $catId);
            $childs = ProductCategory::getByParent($catId)->pluck('cat_id');
            $query->whereNotIn('cat_id', $childs);
        }
        return $query->pluck('cat_name', 'cat_id');
    }

    public static function getById($id)
    {
        return ProductCategory::select('cat_id', 'cat_name')->where('cat_id', $id)->with('uploadImages')->where('cat_publish', config('constants.YES'))->first();
    }

    public static function buildTree($elements, $parentId = 0, $totalChildrenCount = 0, $childrenContainer = 'children')
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['cat_parent'] == $parentId) {
                $children = self::buildTree($elements, $element['cat_id'], $totalChildrenCount, $childrenContainer);
                if ($children) {
                    $element[$childrenContainer] = $children;
                } else {
                    $element[$childrenContainer] = [];
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    public static function buildTree1($elements, $parentId = 0, $totalProductCount = 0, $childrenContainer = 'children')
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['cat_parent'] == $parentId) {
                $children = self::buildTree1($elements, $element['cat_id'], $totalProductCount, $childrenContainer);
                if ($children) {
                    if ($totalProductCount != 0) {
                        $totalProductCount = $totalProductCount + $element['product_counts_count'];
                    }
                    $element[$childrenContainer] = $children;
                    $element['total'] = $totalProductCount;
                } else {
                    $element[$childrenContainer] = [];
                    $element['total'] = $totalProductCount;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public static function getCategoriesByName($searchTerm, $excludeIds)
    {
        return ProductCategory::select('cat_id as value', 'cat_name as label')->where('cat_name', 'like', '%' . $searchTerm . '%')
            ->whereNotIn('cat_id', $excludeIds)
            ->oldest('cat_name')->get()->toArray();
    }

    public static function getCategoriesById($categoryIds)
    {
        $query = ProductCategory::select('cat_id as value', 'cat_name as label', 'cat_id', 'cat_name')->whereIn('cat_id', $categoryIds);
        if (!empty($categoryIds) && count($categoryIds) > 0) {
            $orderByIds = implode(',', $categoryIds);
            $query->orderByRaw("FIELD(cat_id, $orderByIds)");
        }
        return $query->get();
    }

    public static function getCategoriesByParentId($parentId)
    {
        return ProductCategory::getByParent($parentId);
    }
    public static function getNavCategories($parentId)
    {
        return ProductCategory::with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')->select(
            'cat_id',
            'cat_name',
            'cat_display_order',
            'cat_parent'
        )->with('childs')->where('cat_parent', $parentId)->where('cat_publish', config('constants.YES'))->oldest('cat_display_order')->get();
    }

    public static function getAllParentCategories()
    {
        return ProductCategory::select('cat_id', 'cat_name')->where('cat_parent', 0)->where('cat_publish', 1)->get();
    }

    private static function getByParent($parentId)
    {
       
        if(is_array($parentId)){
            $ids = ProductCategory::whereIn('cat_parent', $parentId)->pluck('cat_id')->toArray();
        }else{
            $ids = ProductCategory::where('cat_parent', $parentId)->pluck('cat_id')->toArray();
        }
       
        $unionQuery = ProductCategory::select('cat_id', 'cat_name', 'cat_parent', 'cat_display_order')
            ->whereIn('cat_parent', $ids);
        if(is_array($parentId)){
            return ProductCategory::select('cat_id', 'cat_name', 'cat_parent', 'cat_display_order')
                ->whereIn('cat_parent', $parentId)
                ->union($unionQuery)
                ->oldest('cat_display_order')
                ->get();
        }    
        return ProductCategory::select('cat_id', 'cat_name', 'cat_parent', 'cat_display_order')
            ->where('cat_parent', $parentId)
            ->union($unionQuery)
            ->oldest('cat_display_order')
            ->get();
    }

    public static function getCount()
    {
        return ProductCategory::where('cat_publish', 1)->count();
    }

    public static function getCategoryWithParent()
    {
        $dbprefix = DB::getTablePrefix();
        return ProductCategory::select('product_categories.cat_id', 'product_categories.cat_name', 'product_categories.cat_tax_code', 'product_categories.cat_parent', 'product_categories.cat_publish', 'pro_cat.cat_name as parent_name', 'pro_cat.cat_id as parent_id', DB::raw("(CASE WHEN (" . $dbprefix . "product_categories.cat_publish = 1) THEN 'Yes' ELSE 'No' END) as cat_publish"))
            ->leftjoin('product_categories as pro_cat', 'product_categories.cat_parent', '=', 'pro_cat.cat_id');
    }

    public static function getRecords($fields, $conditions = [], $with = [], $orderBy='')
    {
        $query = ProductCategory::select($fields)->leftJoin('attached_files AS af', function ($join) {
            $join->on('cat_id', '=', 'af.afile_record_id');
            $join->where('af.afile_type', AttachedFile::getConstantVal('productCategoryBanner'));
        });
        if (count($conditions) > 0) {
            $query->where($conditions);
        }
        if (count($with) > 0) {
            foreach ($with as $withDefinition) {
                $query->with($withDefinition);
            }
        }
        if ($orderBy != '') {
            return $query->orderBy('cat_id', $orderBy)->get();
        }
        return $query->oldest('cat_name')->get();
    }
    public static function getRecordsByIds($ids)
    {
        return ProductCategory::whereIn('cat_id', $ids)->pluck('cat_name', 'cat_id');
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'cat_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'cat_updated_by_id')->select(['admin_id', 'admin_username']);
    }

    public function productCounts()
    {
        return $this->hasOne('App\Models\ProductToCategory', 'ptc_cat_id', 'cat_id');
    }
    public static function getCategoriesForApp($fullData = true)
    {
        return Cache::remember(static::cacheKey() . '-apps', 28800, function () use ($fullData) {
            $query = ProductCategory::select(
                'cat_id',
                'cat_name',
                'cat_parent'
            )
            ->where('cat_publish', config('constants.YES'));
                        
            if ($fullData === true) {
                $query->with(array('uploadImages' => function ($q) {
                    $q->select('afile_record_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '?t=', UNIX_TIMESTAMP(afile_updated_at)) as category_image"));
                }))->withCount('products');
            }
            return $query->oldest('cat_display_order')->get()->toArray();
        });
    }

    public static function searchForApp($search = "")
    {
        $query = ProductCategory::select(
            'cat_id',
            'cat_name',
            'cat_parent'
        )
        ->where('cat_publish', config('constants.YES'))->withCount('productCounts');
        if (!empty($search)) {
            $query->where('cat_name', 'like', '%' . $search . '%');
        }
        $query->with(array('uploadImages' => function ($q) {
            $q->select('afile_record_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id, '/thumb', '?t=', UNIX_TIMESTAMP(afile_updated_at)) as category_image"));
        }));
        return $query->oldest('cat_display_order')->limit(5)->get();
    }

    public static function buildTreeForApp($elements, $parentId = 0, $childrenContainer = 'children')
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['cat_parent'] == $parentId) {
                $children = self::buildTreeForApp($elements, $element['cat_id'], $childrenContainer);
                if ($children) {
                    $element[$childrenContainer] = $children;
                } else {
                    $element[$childrenContainer] = [];
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
