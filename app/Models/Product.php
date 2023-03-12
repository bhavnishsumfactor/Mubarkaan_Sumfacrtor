<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Admin\Admin;
use App\Models\Admin\AdminRole;
use App\Models\Option;
use App\Models\ProductOptionVarient;
use App\Models\ProductOption;
use App\Models\ProductToCategory;
use App\Models\ProductSpecification;
use App\Models\AttachedFile;
use App\Models\Configuration;
use App\Helpers\ProductSearchHelper;
use App\Helpers\EmailHelper;
use App\Models\SmsTemplate;
use App\Models\Country;
use App\Models\RelatedProduct;
use App\Models\BuyTogetherProduct;
use DB;
use Carbon\Carbon;
use App\Models\SpecialPrice;
use App\Models\ProductCategory;
use App\Models\UrlRewrite;
use App\Jobs\SendNotification;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends YokartModel
{
    use HasFactory;

    const CONDITION_REFURBISHED = 2;
    const CONDITION_NEW = 1;
    const CONDITION_OLD = 0;

    const PRODUCT_PUBLISHED = 1;
    const PRODUCT_UNPUBLISHED = 0;

    const PHYSICAL_PRODUCT = 1;
    const DIGITAL_PRODUCT = 2;

    const PRODUCT_MEDIA_SIZE = [
        1 => '1', /* 1:1 */
        2 => '1.77', /* 16:9 */
        3 => '0.75', /* 3:4 */
    ];
    const PRODUCT_MEDIA_TYPE = [
        1 => '1:1',
        2 => '16:9',
        3 => '3:4',
    ];
    const PRODUCT_MEDIA_PIXELS = [
        1 => '800x800',
        2 => '800x450',
        3 => '800x1067',
    ];
    const PRODUCT_TYPES = [
        1 => 'Physical',
        2 => 'Digital',
    ];
    const FOUR_COLUMN_PER_PAGE_RECORD = [
        12 => 12,
        24 => 24,
        48 => 48,
        96 => 96,
    ];
    const FIVE_COLUMN_PER_PAGE_RECORD = [
        15 => 15,
        30 => 30,
        60 => 60,
        90 => 90,
    ];

    const PRODUCT_CONDITIONS = [
        self::CONDITION_NEW => 'New',
        self::CONDITION_OLD => 'Old',
        self::CONDITION_REFURBISHED => 'Refurbished'
    ];

    const PRODUCT_CONDITIONS_VALUES = [
        'new' => self::CONDITION_NEW,
        'old' => self::CONDITION_OLD,
        'refurbished' => self::CONDITION_REFURBISHED
    ];

    const SHIPPING_ONLY = 1;
    const PICKUP_ONLY = 2;
    const BOTH = 3;
    const PRODUCT_SHIPPING_TYPE = [
        self::SHIPPING_ONLY => 'Shipping Only',
        self::PICKUP_ONLY => 'Pickup only',
        self::BOTH => 'Both',
    ];

    const METRIC_UNIT = 0;
    const US_UNIT = 1;
    const PRODUCT_UNIT_SYSTEMS = [
        self::METRIC_UNIT => 'Metric',
        self::US_UNIT => 'US'
    ];

    const MINIMUM_QUANTITY = 5;

    const PHYSICAL_PRODUCT_HEADING = [
        'Product Id',
        'Product Title',
        'Brand',
        'Category Id',
        'Category',
        'Description',
        'Model No',
        'Tax Category',
        'Product Condition',
        'Warranty (Days)',
        'Return (Days)',
        'Track Inventory',
        'Stock Alert Qty',
        'Product Delivery Method',
        'Sell when out of stock',
        'COD',
        'Gift Wrap',
        'Min Purchase Qty',
        'Max Purchase Qty',
        'Cost Price',
        'Selling Price',
        'Available Quantity',
        'SKU',
        'Country of Origin',
        'Weight',
        'Weight Unit',
        //'Packages',
        'ISBN',
        'HSN',
        'SAC',
        'UPC',
        'Video URL',
        'Publish',
        'Product published from',
        'Inventory Level'
    ];
    public $timestamps = false;
    protected $primaryKey = 'prod_id';
    protected $fillable = [
        'prod_name', 'prod_type', 'prod_brand_id', 'prod_taxcat_id', 'prod_description', 'prod_cost', 'prod_price', 'prod_stock', 'prod_model', 'prod_condition', 'prod_published', 'prod_publish_from', 'prod_stock_out_sellable', 'prod_cod_available', 'prod_from_origin_country_id', 'prod_sbpkg_id', 'prod_min_order_qty', 'prod_max_order_qty', 'prod_self_pickup', 'prod_sold_count', 'prod_created_on', 'prod_updated_on', 'prod_created_by_id', 'prod_updated_by_id'
    ];


    public static function getTypes()
    {
        $types = Product::PRODUCT_TYPES;
        $isEnabled = Configuration::getValue('PRODUCT_TYPES');
        if ($isEnabled == 1) {
            unset($types[2]);
        } elseif ($isEnabled == 2) {
            unset($types[1]);
        }
        return $types;
    }
    public function productContent()
    {
        return $this->hasOne('App\Models\ProductContent', 'pc_prod_id', 'prod_id');
    }


    public function uploadImages()
    {
        return $this->hasMany('App\Models\AttachedFile', 'afile_record_id', 'prod_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['productImages']);
    }

    public function getUpdatedImage()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'prod_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['productImages'])->orderBy('attached_files.afile_updated_at', 'DESC');
    }
    public function specifications()
    {
        return $this->hasMany('App\Models\ProductSpecification', 'ps_product_id', 'prod_id');
    }

    public function productCategory()
    {
        return $this->hasOne('App\Models\ProductToCategory', 'ptc_prod_id', 'prod_id');
    }
    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'prod_brand_id', 'brand_id');
    }
    public function taxgroup()
    {
        return $this->belongsTo('App\Models\TaxGroup', 'prod_taxcat_id', 'taxgrp_id');
    }
    public function urlRewrite()
    {
        return $this->belongsTo('App\Models\UrlRewrite', 'prod_id', 'urlrewrite_record_id')->where('urlrewrite_type', UrlRewrite::PRODUCT_TYPE);
    }

    public function digitalFiles()
    {
        return $this->hasMany('App\Models\DigitalFileRecord', 'dfr_record_id', 'prod_id');
    }
    public function varients()
    {
        return $this->hasMany('App\Models\ProductOptionVarient', 'pov_prod_id', 'prod_id');
    }
    public function options()
    {
        return $this->hasMany('App\Models\ProductOption', 'poption_prod_id', 'prod_id');
    }
    public function colorOptions()
    {
        return  $this->hasMany('App\Models\ProductOptionVarient', 'pov_prod_id', 'prod_id')
            ->join('option_to_varients', 'otv_pov_id', 'pov_id')
            ->join('product_option_names', 'otv_opn_id', 'opn_id')
            ->join('product_options', function ($sql) {
                $sql->on('poption_opn_id', 'opn_id')
                    ->join('options', 'poption_option_id', 'option_id')->whereRaw('pov_prod_id = poption_prod_id');
            })
            ->where('pov_publish', config('constants.YES'))->where('option_has_image', config('constants.YES'))->groupBy('opn_id');
    }
    public function favouriteCount()
    {
        return $this->belongsTo('App\Models\UserFavouriteProduct', 'prod_id', 'ufp_prod_id');
    }
    public static function getProductMedia($id)
    {
        return Option::join('product_options as po', 'po.poption_option_id', 'options.option_id')
            ->join('product_option_names as pname', 'opn_id', 'poption_opn_id')
            ->select('po.poption_id', 'pname.opn_value as poption_name')
            ->where(['po.poption_prod_id' => $id, 'options.option_has_image' => 1])
            ->get();
    }
    public function defaultVarient()
    {
        return $this->hasOne('App\Models\ProductOptionVarient', 'pov_prod_id', 'prod_id')->where('pov_publish', '=', config('constants.YES'))
            ->where('pov_default', '=', config('constants.YES'));
    }

    public static function getProducts($request, $seoInfo = 0, $withImages = false)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        if ($seoInfo == 0) {
            $query = Product::selectRaw(
                'prod_id,
                prod_updated_on,
                prod_stock,
                prod_price,
                prod_brand_id, 
                prod_name,
                prod_type,
                prod_published,
                prod_min_order_qty,
                prod_from_origin_country_id,
                prod_sbpkg_id,
                pc_substract_stock,
                pc_weight_unit,
                cat_id,
                cat_name,
                brand_name,
                pov_code,
                coalesce(`pov_price`, `prod_price`) as price,
                coalesce(`pov_quantity`, `prod_stock`) as qty,
                pc_weight'
            )->with('varients')->withCount('brand');
            $query->leftJoin('brands', 'products.prod_brand_id', 'brands.brand_id');

            $query->leftJoin('product_to_categories as ptc', function ($catQuery) {
                $catQuery->on('ptc.ptc_prod_id', 'products.prod_id')
                    ->join('product_categories as pc', 'ptc.ptc_cat_id', 'pc.cat_id');
            });
            $query->join('product_contents as content', 'content.pc_prod_id', 'products.prod_id');
            static::defaultVariant($query);
        } else {
            $query = Product::select('prod_id as record_id', 'prod_name as record_title', 'urlrewrite_id', 'urlrewrite_original', 'urlrewrite_custom')
                ->leftJoin('url_rewrites', function ($sql) {
                    $sql->on('urlrewrite_record_id', 'prod_id')->where('urlrewrite_type', UrlRewrite::PRODUCT_TYPE);
                });
            if ($withImages) {
                $query->has('uploadImages', '>', 0);
            }
        }
        if (!empty($request['search'])) {
            if ($seoInfo == 0) {
                static::searchProductsByKeywords($query, $request['search']);
            } else {
                $query->where('prod_name', 'like', '%' . $request['search'] . '%');
            }
        }
        if (!empty($request['category_id'])) {
            $query->where('ptc.ptc_cat_id', $request['category_id']);
        }
        if (!empty($request['brand_id'])) {
            $query->where('prod_brand_id', $request['brand_id']);
        }
        if (!empty($request['sorting-by']) && !empty($request['sorting-type'])) {
            $query->orderBy($request['sorting-by'], $request['sorting-type']);
        }
        if (empty($request['search']) && empty($request['sorting-by'])) {
            $query->latest('prod_id');
        }
        return $query->paginate((int) $per_page);
    }
    public static function defaultVariant($query)
    {
        $query->leftjoin('product_option_varients as varients', function ($join) {
            $join->on('varients.pov_prod_id', '=', 'products.prod_id')
                ->where('varients.pov_publish', '=', config('constants.YES'))
                ->where('varients.pov_default', '=', config('constants.YES'));
        });
    }
    public static function getIncompleteProducts($digitalProducts)
    {
        $query = Product::select('prod_id')->join('product_contents as content', 'content.pc_prod_id', 'products.prod_id');

        static::defaultVariant($query);
        $query->whereRaw('coalesce(`pov_price`, `prod_price`) = 0 or coalesce(`pov_quantity`, `prod_stock`) is null ');

        if ($digitalProducts == 1) {
            $query->whereRaw('prod_from_origin_country_id is null or pc_weight_unit is null or prod_sbpkg_id is null or pc_weight is null');
        }
        if ($digitalProducts != 3) {
            $query->orWhere('prod_type', '!=', $digitalProducts);
        }
        return $query->limit(1)->count();
    }
    public static function getOutOfStockProducts()
    {
        $query = Product::select('prod_id')->join('product_contents as content', 'content.pc_prod_id', 'products.prod_id');
        static::defaultVariant($query);
        $query->where('pc_threshold_stock_level', 1)
            ->whereRaw('pc_substract_stock >= coalesce(`pov_quantity`, `prod_stock`)');
        return $query->limit(1)->count();
    }

    public static function getErrorsProducts($request, $digitalProducts)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = Product::selectRaw(
            'prod_id,
            prod_updated_on,
            prod_stock,
            prod_price,
            prod_brand_id, 
            prod_name,
            prod_type,
            prod_published,
            prod_min_order_qty,
            prod_from_origin_country_id,
            prod_sbpkg_id,
            pc_substract_stock,
            pc_weight_unit,
            cat_id,
            pov_code,
            coalesce(`pov_price`, `prod_price`) as price,
            coalesce(`pov_quantity`, `prod_stock`) as qty,
            pc_weight'
        )->with('varients')->withCount('brand')->join('product_contents as content', 'content.pc_prod_id', 'products.prod_id');
        $query->leftJoin('product_to_categories as ptc', function ($catQuery) {
            $catQuery->on('ptc.ptc_prod_id', 'products.prod_id')
                ->join('product_categories as pc', 'ptc.ptc_cat_id', 'pc.cat_id');
        });
        static::defaultVariant($query);
        $query->whereRaw('coalesce(`pov_price`, `prod_price`) = 0 or coalesce(`pov_quantity`, `prod_stock`) is null  or prod_min_order_qty is null or prod_max_order_qty is null');
        if ($digitalProducts == 1) {
            $query->whereRaw('prod_from_origin_country_id is null or pc_weight_unit is null or prod_sbpkg_id is null or pc_weight is null');
        }
        if ($digitalProducts != 3) {
            $query->orWhere('prod_type', '!=', $digitalProducts);
        }

        if (!empty($request['sorting-by']) && !empty($request['sorting-type'])) {
            $query->orderBy($request['sorting-by'], $request['sorting-type']);
        }
        $query->latest('prod_id');
        return $query->paginate((int) $per_page);
    }
    public static function getAlertProducts($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = Product::selectRaw(
            'prod_id,
            prod_updated_on,
            prod_stock,
            prod_price,
            prod_brand_id, 
            prod_name,
            prod_type,
            prod_published,
            prod_min_order_qty,
            prod_from_origin_country_id,
            prod_sbpkg_id,
            pc_substract_stock,
            pc_weight_unit,
            cat_id,
            pov_code,
            coalesce(`pov_price`, `prod_price`) as price,
            coalesce(`pov_quantity`, `prod_stock`) as qty,
            pc_weight'
        )->with('varients')->withCount('brand')->join('product_contents as content', 'content.pc_prod_id', 'products.prod_id');
        $query->leftJoin('product_to_categories as ptc', function ($catQuery) {
            $catQuery->on('ptc.ptc_prod_id', 'products.prod_id')
                ->join('product_categories as pc', 'ptc.ptc_cat_id', 'pc.cat_id');
        });
        static::defaultVariant($query);
        $query->where('pc_threshold_stock_level', 1)
            ->whereRaw('pc_substract_stock >= coalesce(`pov_quantity`, `prod_stock`)');

        if (!empty($request['sorting-by']) && !empty($request['sorting-type'])) {
            $query->orderBy($request['sorting-by'], $request['sorting-type']);
        }
        $query->groupBy('prod_id');
        $query->latest('prod_id');
        return $query->paginate((int) $per_page);
    }
    public static function searchProductsByKeywords($query, $keyword)
    {
        $keywords = explode(',', $keyword);
        $fieldConditions = "";
        $escapeWords = array("in", "it", "a", "the", "of", "or", "i", "you", "he", "me", "us", "they", "she", "to", "but", "that", "this", "those", "then");
        $totalWordInStrings = count($keywords);
        $processWords = 1;
        foreach ($keywords as $val) {
            $val = cleanString($val);
            $fieldConditions .= "( case when prod_name LIKE '%" . $val . "%' then 10 else 0 end )  +
                    ( case when brand_name LIKE '%" . $val . "%' then 10 else 0 end ) +
                    ( case when cat_name LIKE '%" . $val . "%' then 10 else 0 end ) +
                    ( case when opn_value LIKE '%" . $val . "%' then 5 else 0 end ) +
                    ( case when prod_description LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pc_isbn LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pc_hsn LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pc_sac LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pc_upc LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pc_sku LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pov_sku LIKE '%" . $val . "%' then 1 else 0 end )";
            if ($totalWordInStrings > $processWords) {
                $fieldConditions .= '+';
                $processWords++;
            }
        }

        $query->leftjoin('product_options as options', function ($join) {
            $join->on('options.poption_prod_id', 'products.prod_id')
                ->join('product_option_names as pname', 'opn_id', 'poption_opn_id');
        });

        $query->leftJoin('tax_groups as tg', 'products.prod_taxcat_id', 'tg.taxgrp_id');
        static::searchConditions($query, $keyword, $escapeWords);
        $query->selectRaw($fieldConditions . 'as relevancy');
        $query->orderBy('relevancy', 'desc');
        $query->groupBy('prod_id');
        return $query;
    }

    public static function searchConditions($query, $value, $escapeWords = array())
    {
        $valueArray = explode(',', $value);

        $query->where(function ($condistionQuery) use ($valueArray, $escapeWords, $value) {
            $value  = cleanSpaces($value);

            $condistionQuery->orWhereRaw("REPLACE(`brand_name`, ' ', '') LIKE ?", [$value])
                ->orWhereRaw("REPLACE(`prod_name`, ' ', '') LIKE ?", ['%' . $value . '%'])
                ->orWhereRaw("REPLACE(`taxgrp_name`, ' ', '') LIKE ?", ['%' . $value . '%'])
                ->orWhereRaw("REPLACE(`cat_name`, ' ', '') LIKE ?", ['%' . $value . '%'])
                ->orWhereRaw("REPLACE(`prod_description`, ' ', '') LIKE ?", ['%' . $value . '%'])
                ->orWhere('pc_isbn', 'like', $value)
                ->orWhere('pc_sku', 'like', $value)
                ->orWhere('pov_sku', 'like', $value)
                ->orWhere('pc_hsn', 'like', $value)
                ->orWhere('pc_sac', 'like', $value)
                ->orWhere('pc_upc', 'like', $value)
                ->orWhere('opn_value', 'like', $value);

            if (count($valueArray) > 1) {
                foreach ($valueArray as $keyword) {
                    if (!in_array($keyword, $escapeWords)) {
                        $keyword  = cleanSpaces($keyword);
                        $condistionQuery->orWhereRaw("REPLACE(`brand_name`, ' ', '') LIKE ?", [$keyword])
                            ->orWhereRaw("REPLACE(`cat_name`, ' ', '') LIKE ?", ['%' . $keyword . '%'])
                            ->orWhereRaw("REPLACE(`prod_name`, ' ', '') LIKE ?", ['%' . $keyword . '%'])
                            ->orWhere('opn_value', 'like', $keyword);
                    }
                }
            }
        });

        return $query;
    }
    public static function deleteProducts($productId)
    {
        Product::where('prod_id', $productId)->delete();
        ProductContent::where('pc_prod_id', $productId)->delete();
        ProductOption::where('poption_prod_id', $productId)->delete();
        ProductOptionVarient::where('pov_prod_id', $productId)->delete();
        ProductToCategory::where('ptc_prod_id', $productId)->delete();
        ProductSpecification::where('ps_product_id', $productId)->delete();
        RelatedProduct::where(function ($query) use ($productId) {
            $query->orWhere('related_product_id', $productId)
                ->orWhere('related_recommend_product_id', $productId);
        })->delete();
        BuyTogetherProduct::where(function ($query) use ($productId) {
            $query->orWhere('btp_product_id', $productId)
                ->orWhere('btp_recommend_product_id', $productId);
        })->delete();

        AttachedFile::deleteFileById(AttachedFile::getConstantVal('productImages'), $productId);
    }

    public static function getListingObj($filters, $fields = '', $conditions = true, $searchKeyword = [], $published = true, $type = '')
    {
        $product  = new ProductSearchHelper($fields, $published);
        $product->joinContent();
        $product->joinProductToCategory();
        $product->joinBrand();
        $product->joinTax();
        $product->joinVarient($conditions, $searchKeyword, $type);
      //  $product->joinExcludeSepcialPrice();
        $product->joinProductSepcialPrice();
        $product->joinBrandSepcialPrice();
        $product->joinCategorySepcialPrice();


        foreach ($filters as $key => $val) {
            switch ($key) {
                case 'categories':
                    if (!empty($val)) {
                        $ids = ProductCategory::getSubcategories($val);
                        $product->addCategoryCondition($ids);
                    }
                    break;
                case 'prod_id':
                    $product->addCondition($val);
                    break;
                case 'products':
                    $product->addCondition($val);
                    break;    
                case 'options':
                    if (!empty($val)) {
                        $product->addOptionCondition($val);
                    }
                    break;
                case 'brands':
                    if (!empty($val)) {
                        $product->addBrandCondition($val);
                    }
                    break;
                case 'collection':
                    if (!empty($val)) {
                        $explde = explode('|', $val);
                        $sort = (!empty($filters['sortBy'])) ? true : false;
                        $product->joinProductCollections($explde[0], $explde[1], $sort);
                    }
                    break;
                case 'prod-conditions':
                    $product->addProdCondition($val);
                    break;
                case 'price-range':
                    if (!empty($val)) {
                        $product->addPriceRangeCondition($val);
                    }
                    break;
                
            }
        }
        $product->emptyConditions();
        return $product;
    }

    public static function getPrice()
    {
        $dbprefix = DB::getTablePrefix();
        $prodCondition = 'if(' . $dbprefix . 'prodsp.splprice_type = ' . SpecialPrice::FLAT_TYPE . ', if((coalesce(`pov_price`, `prod_price`) - ' . $dbprefix . 'prodsp.splprice_amount) < 0, 0 , (coalesce(`pov_price`, `prod_price`) - ' . $dbprefix . 'prodsp.splprice_amount)), (coalesce(`pov_price`, `prod_price`) - coalesce(`pov_price`, `prod_price`) * ' . $dbprefix . 'prodsp.splprice_amount /100) )';
        $brandCondition = 'if(' . $dbprefix . 'brandsp.splprice_type = ' . SpecialPrice::FLAT_TYPE . ', if((coalesce(`pov_price`, `prod_price`) - ' . $dbprefix . 'brandsp.splprice_amount) < 0, 0 , (coalesce(`pov_price`, `prod_price`) - ' . $dbprefix . 'brandsp.splprice_amount)), (coalesce(`pov_price`, `prod_price`) - coalesce(`pov_price`, `prod_price`) * ' . $dbprefix . 'brandsp.splprice_amount /100) )';
        $catCondition = 'if(' . $dbprefix . 'catsp.splprice_type = ' . SpecialPrice::FLAT_TYPE . ', if((coalesce(`pov_price`, `prod_price`) - ' . $dbprefix . 'catsp.splprice_amount) < 0, 0 , (coalesce(`pov_price`, `prod_price`) - ' . $dbprefix . 'catsp.splprice_amount)), (coalesce(`pov_price`, `prod_price`) - coalesce(`pov_price`, `prod_price`) * ' . $dbprefix . 'catsp.splprice_amount /100) )';
        return 'coalesce(' . $prodCondition . ', ' . $brandCondition . ', ' . $catCondition . ', pov_price, prod_price) as finalprice, coalesce(pov_price, prod_price) as price, ROUND(((coalesce(pov_price, prod_price) - coalesce(' . $prodCondition . ', ' . $brandCondition . ', ' . $catCondition . ', pov_price, prod_price)) / coalesce(pov_price, prod_price) * 100)) as discount, coalesce(`pov_quantity`, `prod_stock`) as totalstock';
    }


    public static function getListing($fields, $filters, $records = 0, $condition = true, $searchItem = '')
    {
        
        if (!empty($searchItem)) {
            
            $query = self::getListingSearchObject($filters, $fields, $searchItem);
        } else {
            $query = static::getListingObj($filters, $fields, $condition);
            $query->joinUrlRewrite();
            $query->includeFavouriteCount();
        }
    
        if (!empty($filters['sortBy']) &&  $filters['sortBy'] != 'new') {
            $sortBy = $filters['sortBy'];
            if ($sortBy == 'priceDesc') {
                $query->orderBy('finalprice', 'DESC');
            } elseif ($sortBy == 'priceAsc') {
                $query->orderBy('finalprice', 'ASC');
            } elseif ($sortBy == 'popularity') {
                $query->orderBy('prod_sold_count', 'DESC');
            } elseif ($sortBy == 'rating') {
                $query->joinReviews();
                $query->addAdditionalFields('AVG(preview_rating) as avg_preview_rating');
                $query->orderBy('avg_preview_rating', 'DESC');
            } elseif ($sortBy == 'discounted') {
                $query->orderBy('discount', 'Desc');
            } elseif ($sortBy == 'alphabetically') {
                $query->orderBy('prod_name', 'ASC');
            }
        } else {
            $query->orderBy('prod_id', 'DESC');
        }
       
        $query->addGroupByCondition('prod_id');
        $query->setPageSize($records);
  
        return $query->getRecords();
    }
    public static function getAllProducts($filters, $fields, $condition = true, $searchItem = '')
    {
        if (!empty($searchItem)) {
            $query = self::getListingSearchObject($filters, $fields, $searchItem);
        } else {
            $query = self::getListingObj($filters, $fields, $condition);
        }
        $query->orderBy('pov_default', 'Desc');
        return  $query->getRecords();
    }
    public static function getListingSearchObject($filters, $fields, $value, $orderBy = true)
    {
       
        $keywordString = explode(' ', $value);
        $options = ProductOption::whereIn('pname.opn_value', $keywordString)
            ->join('product_option_names as pname', 'opn_id', 'poption_opn_id')
            ->join('options as p', 'option_id', 'poption_option_id')
            ->select('poption_opn_id')
            ->get()->keyBy('poption_opn_id')->toArray();
        $fieldConditions = "";
        $escapeWords = array("in", "it", "a", "the", "of", "or", "i", "you", "he", "me", "us", "they", "she", "to", "but", "that", "this", "those", "then");
        $totalWordInStrings = count($keywordString);
        $processWords = 1;
      
        foreach ($keywordString as $val) {
            $val = cleanString($val);

            $fieldConditions .= "( case when prod_name LIKE '%" . $val . "%' then 10 else 0 end )  +
                    ( case when brand_name LIKE '%" . $val . "%' then 10 else 0 end ) +
                    ( case when cat_name LIKE '%" . $val . "%' then 10 else 0 end ) +
                    ( case when opn_value LIKE '%" . $val . "%' then 5 else 0 end ) +
                    ( case when prod_description LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pc_isbn LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pc_hsn LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pc_sac LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pc_upc LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when pov_sku LIKE '%" . $val . "%' then 1 else 0 end )";
            if ($totalWordInStrings > $processWords) {
                $fieldConditions .= '+';
                $processWords++;
            }
        }

        $fields = $fieldConditions . " as relevancy, " . $fields;

        $query = static::getListingObj($filters, $fields, false, $options);
        $query->joinOptions();
        $query->addSearchCondition($value, $escapeWords);
     
     
        if (empty($filters['sortBy']) && $orderBy == true) {
            $query->orderBy('relevancy', 'desc');
        }

     
        return $query;
    }

    public static function getPriceByVarientCode($prodId, $varientCode)
    {
        $fields = static::getPrice();
        $filters = [];
        if (substr($varientCode, -1) != "|") {
            $varientCode = $varientCode . '|';
        }
        $query = static::getListingObj($filters, $fields, false);
        $query->addCondition($varientCode, 'pov_code');
        return $query->firstRecord();
    }

    public static function getDataByIds($productIds, $filters = [], $paginate = false, $published = true)
    {
        $fields =  'prod_id, cat_id, cat_name, prod_updated_on,prod_name, prod_type, prod_price, pov_default, 
        prod_stock_out_sellable, prod_min_order_qty, prod_cod_available, pov_code,brand_id,cat_id,
         prod_max_order_qty,' . Product::getPrice();
        $query = static::getListingObj($filters, $fields, true, [], $published);
        $query->addCondition($productIds);
        if (!empty($productIds) && count($productIds) > 0) {
            $query->orderByRaw('prod_id', $productIds);
        }
        if ($paginate == true) {
            $query->setPageSize();
        }
        $query->addGroupByCondition('prod_id');
        return $query->getRecords();
    }
    public static function productById($prodId, $fields, $filters = [], $prodOptionCode = 0, $published = false)
    {
        $conditions = true;
        if ($prodOptionCode != 0) {
            $conditions = false;
            if (substr($prodOptionCode, -1) != "|") {
                $prodOptionCode = $prodOptionCode . '|';
            }
        }

        $query = static::getListingObj($filters, $fields, $conditions, [], $published);
        $query->includeFavouriteCount();
        $query->joinColourOption();
        $query->addCondition($prodId);
        if (!empty($prodOptionCode)) {
            $query->addCondition($prodOptionCode, 'pov_code');
        }
        return $query->firstRecord();
    }

    public static function productObjById($prodId, $fields, $filters = [], $prodOptionCode = 0, $published = false)
    {
        $conditions = true;
        if ($prodOptionCode != 0) {
            $conditions = false;
            if (substr($prodOptionCode, -1) != "|") {
                $prodOptionCode = $prodOptionCode . '|';
            }
        }

        $query = static::getListingObj($filters, $fields, $conditions, [], $published);
        $query->includeFavouriteCount();
        $query->joinColourOption();
        $query->addCondition($prodId);
        if (!empty($prodOptionCode)) {
            $query->addCondition($prodOptionCode, 'pov_code');
        }
        return $query;
    }

    public static function getOptionsFilters($filters, $condition, $searchItem)
    {
        $options = ProductOption::join('options', 'options.option_id', 'product_options.poption_option_id')->groupBy('option_id')->pluck('option_name', 'option_id');

        $products = [];
        foreach ($options as $opkey => $option) {
            $optionName = $option;
            if ($opkey == 1) {
                $optionName = "Color";
            } elseif ($opkey == 2) {
                $optionName = "Size";
            }
            $records = self::optionFiltersRecords($filters, $condition, $searchItem, $opkey);
            if (count($records['data']) > 0) {
                $products[$opkey] = $records;
                $products[$opkey]['name'] = $optionName;
            }
        }

        return  $products;
    }
    public static function optionFiltersRecords($filters, $condition, $searchItem, $typeId, $searchedItems = [], $loadMore = false)
    {
        if (isset($filters['options'])) {
            unset($filters['options']);
        }
        if ($loadMore == true) {
            if (isset($filters['brands'])) {
                unset($filters['brands']);
            }
        }
        $paginatRecords = 6;
        $fields = 'opn_value, opn_id, opn_color_code, ' . Product::getPrice();
        if (!empty($searchItem)) {
            $query = self::getListingSearchObject($filters, $fields, $searchItem, false);
        } else {
            $query = self::getListingObj($filters, $fields, $condition);
            $query->joinOptionToVarient();
        }

        $query->addCondition($typeId, 'poption_option_id');
        if (count($searchedItems) > 0) {
            $query->orderByRaw('opn_id', $searchedItems);
            $paginatRecords = $paginatRecords +  count($searchedItems);
        }
        $query->orderBy('opn_value', 'ASC');
        $query->addGroupByCondition('opn_id');
        $query->setPageSize($paginatRecords);

        return $query->getRecords()->toArray();
    }
    public static function getDataByIdsForApp($productIds, $filters = [], $paginate = false, $published = true)
    {
        $fields =  'prod_id, cat_id, cat_name, prod_updated_on,prod_name, prod_type, prod_price, pov_default, 
        prod_stock_out_sellable, prod_min_order_qty, prod_cod_available, pov_code, pov_display_title,brand_id,cat_id,
         prod_max_order_qty,' . Product::getPrice() . ',' .
         DB::Raw("CONCAT('" . url('') . "', '/yokart/app/product/image/', prod_id, '/', IF(pov_code IS NULL, '0', pov_id), '?t=', UNIX_TIMESTAMP(prod_updated_on)) as prod_image") . ',' .
         DB::Raw("IF(pov_code IS NULL, '', pov_code) as pov_code") . ',' .
         DB::Raw("IF(pov_display_title IS NULL, '', pov_display_title) as pov_display_title");
        $query = static::getListingObj($filters, $fields, true, [], $published);
        $query->addCondition($productIds);
        $query->joinUrlRewrite();
        $query->includeFavouriteCount();
        if (!empty($productIds) && count($productIds) > 0) {
            $query->orderByRaw('prod_id', $productIds);
        }
        if ($paginate == true) {
            $query->setPageSize();
        }
        $query->addGroupByCondition('prod_id');
        return $query->getRecords();
    }
    public static function getOptionsFiltersByType($filters, $condition, $searchedId, $searchedItem = '')
    {
        if (isset($filters['options'])) {
            unset($filters['options']);
        }
        $fields = 'opn_id as value, opn_value as label, opn_color_code, ' . Product::getPrice();
        if (!empty($searchedItem)) {
            $query = self::getListingSearchObject($filters, $fields, $searchedItem, false);
        } else {
            $query = self::getListingObj($filters, $fields, false);
            $query->joinOptionToVarient();
        }
        $query->addCondition($searchedId, 'poption_option_id');
        $query->orderBy('opn_value', 'ASC');
        return $query->getRecords();
    }

    public static function getBrandFilters($filters, $paginate, $condition, $searchItem, $selectedFilters = [])
    {
        if (isset($filters['brands']) &&  $filters['searchedBy'] != "brands") {
            unset($filters['brands']);
        }
     
        $paginatRecords = 6;
        $fields = 'brand_id as value, brand_name as label, ' . self::getPrice();

        if (!empty($searchItem)) {
            $query = self::getListingSearchObject($filters, $fields, $searchItem, false);
        } else {
            $query = static::getListingObj($filters, $fields, $condition);
        }

        $query->addCondition(config('constants.YES'), 'brand_publish');
        if (count($selectedFilters) > 0) {
            $paginatRecords = $paginatRecords +  count($selectedFilters);
            $query->orderByRaw('prod_brand_id', $selectedFilters);
        }

        $query->addGroupByCondition('prod_brand_id');
        $query->orderBy('brand_name', 'ASC');

        if ($paginate == true) {
            $query->setPageSize($paginatRecords);
        }

        
        return $query->getRecords();
    }

    public static function getBrandFiltersForApp($filters, $paginate, $condition, $searchItem, $selectedFilters = [])
    {
        if (isset($filters['brands']) /*&&  $filters['searchedBy'] != "brands" */) {
            return Brand::select('brand_id as value', 'brand_name as label')->whereIn('brand_id', $filters['brands'])->get();
        }
     
        $paginatRecords = 6;
        $fields = 'brand_id as value, brand_name as label, ' . self::getPrice();

        if (!empty($searchItem)) {
            $query = self::getListingSearchObject($filters, $fields, $searchItem, false);
        } else {
            $query = static::getListingObj($filters, $fields, $condition);
        }

        $query->addCondition(config('constants.YES'), 'brand_publish');
        if (count($selectedFilters) > 0) {
            $paginatRecords = $paginatRecords +  count($selectedFilters);
            $query->orderByRaw('prod_brand_id', $selectedFilters);
        }

        $query->addGroupByCondition('prod_brand_id');
        $query->orderBy('brand_name', 'ASC');

        if ($paginate == true) {
            $query->setPageSize($paginatRecords);
        }

        
        return $query->getRecords();
    }

    public static function getConditionsFilters($filters, $condition, $searchItem)
    {
        $fields = 'prod_condition, ' . self::getPrice();

        if (!empty($searchItem)) {
            $query = self::getListingSearchObject($filters, $fields, $searchItem);
        } else {
            $query = static::getListingObj($filters, $fields, $condition);
        }
        $query->addGroupByCondition('prod_condition');
        $query->addProdCondition(array_keys(self::PRODUCT_CONDITIONS));
        return $query->getRecords();
    }
    public static function getCategoryFilters($filters, $condition, $searchItem, $catListView = false)
    {
        if ($catListView == true) {
            $fields = 'cat_id, cat_name, ' . Product::getPrice();
            if (!empty($searchItem)) {
                $query = self::getListingSearchObject($filters, $fields, $searchItem);
            } else {
                $query = static::getListingObj($filters, $fields, $condition);
            }
            $query->addGroupByCondition('cat_id');
            $query->orderBy('cat_name', 'ASC');
            return $query->getRecords();
        }
        return ProductCategory::getParentCategories();
    }

    public static function getProductsByName($searchTerm, $excludeIds, $categoryid = 0, $conditions = true, $groupBy = true)
    {
        $searchTerm =  Str::lower($searchTerm);
        $fields =  'prod_id as value, prod_name as label, pov_display_title, pov_code, ' . Product::getPrice();
        $query = static::getListingObj([], $fields, $conditions);

        $query->addSearchFieldCondition('prod_name', $searchTerm);
        if (count($excludeIds) > 0) {
            $query->addNotInCondition('prod_id', $excludeIds);
        }
        if ($groupBy == true) {
            $query->addGroupByCondition('prod_id');
        }

        if ($categoryid != 0) {
            $query->addCondition($categoryid, 'ptc_cat_id');
        }
        return $query->getRecords();
    }

    public static function getProductsBySearch($searchTerm, $limit = 5, $excludeIds = [])
    {
        $searchTerm =  Str::lower($searchTerm);
        $fields = 'prod_id, prod_name, ' . self::getPrice();
        $query = static::getListingObj([], $fields);
 
        $query->addSearchFieldCondition('prod_name', $searchTerm);
        if (!empty($excludeIds)) {
            $query->addNotInCondition('prod_id', $excludeIds);
        }
        $query->addGroupByCondition('prod_id');
        $query->getTake($limit);
        return $query->getRecords()->toArray();
    }

    public static function getProductsById($productIds)
    {
        $query = Product::select('prod_id as value', 'prod_name as label')->whereIn('prod_id', $productIds);
        if (!empty($productIds) && count($productIds) > 0) {
            $orderByIds = implode(',', $productIds);
            $query->orderByRaw("FIELD(prod_id, $orderByIds)");
        }
        return $query->get();
    }


    public static function generalInfoById($id)
    {
        $productArray = ['prod_id', 'prod_name', 'prod_type', 'prod_brand_id', 'prod_description', 'prod_model', 'prod_taxcat_id', 'prod_condition', 'pc_return_age', 'pc_warranty_age', 'pc_cancellation_age', 'ptc_cat_id as prod_cat_id', 'prod_created_by_id', 'prod_updated_by_id', 'prod_created_on', 'prod_updated_on'];
        return Product::join('product_contents as content', 'content.pc_prod_id', 'products.prod_id')
            ->leftJoin('product_to_categories as category', 'category.ptc_prod_id', 'products.prod_id')
            ->with(['createdAt', 'updatedAt'])
            ->select($productArray)->where('prod_id', $id)->first();
    }

    public static function getRecordById($id, $selectArray)
    {
        return Product::join('product_contents as content', 'content.pc_prod_id', 'products.prod_id')
            ->select($selectArray)->where('prod_id', $id)->with(['createdAt', 'updatedAt'])->first();
    }
    public static function getBuyTogetherProducts($fields, $productId, $published = true)
    {
        $filters = [];
        $query = static::getListingObj($filters, $fields, true, [], $published);
        $query->joinBuyTogetherProducts();
        $query->addCondition($productId, 'btp_product_id');
        $query->addGroupByCondition('prod_id');
        return $query->getRecords();
    }

    public static function getBuyTogetherProductIds($fields, $productIds, $records)
    {
        $filters = [];
        $query = static::getListingObj($filters, $fields);
        $query->joinBuyTogetherProducts();
        $query->addCondition($productIds, 'btp_product_id');
        $query->setPageSize($records);
        $query->orderBy('prod_sold_count', 'DESC');
        $query->orderBy('finalprice', 'ASC');
        $query->addGroupByCondition('prod_id');
        return $query->getRecords();
    }

    public static function getRelatedProducts($fields, $productId, $published = true)
    {
        $filters = [];
        $query = static::getListingObj($filters, $fields, true, [], $published);
        $query->joinRelatedProducts();
        $query->includeFavouriteCount();
        $query->addCondition($productId, 'related_product_id');
        $query->addGroupByCondition('prod_id');
        return $query->getRecords();
    }
    public static function getFavoriteProductByUserId($userId, $request = [], $row = 0)
    {
        $filters = [];
        $fields =  'ufp_id,prod_id, prod_name, prod_price,  pov_id, pov_default, 
        prod_stock_out_sellable, prod_min_order_qty, pov_code, pov_display_title,brand_id,cat_id,
         prod_max_order_qty,cat_name,ufp_created_at, ' . self::getPrice();
        $query = static::getListingObj($filters, $fields, false);
        $query->getLastUpdatedImage();
        $query->includeFavouriteCount();
        $query->addCondition($userId, 'fav.ufp_user_id');
        if (isset($request['filters']) && !empty($request['filters'])) {
            switch ($request['filters']) {
                case 'latest':
                    $query->orderBy('ufp_id', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('ufp_id', 'ASC');
                    break;
                case 'bestSelling':
                    $query->orderBy('prod_sold_count', 'DESC');
                    break;
                case 'mostDiscounted':
                    $query->orderBy('discount', 'Desc');
                    break;
                case 'alphaAsc':
                    $query->orderBy('prod_name', 'ASC');
                    break;
                case 'alphaDesc':
                    $query->orderBy('prod_name', 'DESC');
                    break;
                case 'priceDesc':
                    $query->orderBy('finalprice', 'DESC');
                    break;
                case 'priceAsc':
                    $query->orderBy('finalprice', 'ASC');
                    break;
            }
        }
        $query->addGroupByCondition('ufp_id');
        $query->joinUrlRewrite();
        if ($row != 0) {
            $query->getSkip($row);
            $query->getTake(12);
        } else {
            $query->setPageSize(12);
        }
        return $query->getRecords();
    }

    public function getProductOptions()
    {
        return $this->hasMany('App\Models\ProductOption', 'poption_prod_id', 'prod_id')
            ->join('product_option_names as pname', 'opn_id', 'poption_opn_id');
    }

    public static function getCount()
    {
        return Product::where('prod_published', config('constants.YES'))->count();
    }
    public static function searchBykeyWords()
    {
        return Product::where('prod_published', config('constants.YES'))->count();
    }

    public static function getLessQuantityProducts()
    {
        return  Product::selectRaw('prod_id,prod_name,coalesce(`pov_quantity`, `prod_stock`) AS qty,pov_display_title, prod_published')->leftjoin('product_option_varients as varients', function ($join) {
            $join->on('varients.pov_prod_id', '=', 'products.prod_id')
                ->where('varients.pov_publish', '=', config('constants.YES'));
        })->having('qty', '<=', self::MINIMUM_QUANTITY)->get()->toArray();
    }

    public static function getSavedProductByUserId($userId, $type, $cartSesstion = '', $shippingType = 0)
    {
        $fields = 'usp_id, pov_id,prod_updated_on, pov_display_title, prod_min_order_qty,prod_stock_out_sellable, prod_name,prod_max_order_qty, prod_self_pickup, pov_code, prod_id, pc_gift_wrap_available, ' . Product::getPrice();
        $filters = [];
        $query = static::getListingObj($filters, $fields, false);
        $query->joinSavedProducts();
        $query->addSavedProductsCondition();
        $query->addCondition($userId, 'sp.usp_user_id');
        $query->addSavedProductCondition($type, $cartSesstion);
        if ($shippingType != 0) {
            $query->addCondition($shippingType, 'prod_self_pickup');
        }
        return $query->getRecords();
    }
    public static function getSavedProductById($id)
    {
        $filters = [];
        $fields =  'prod_id, prod_name, prod_price, prod_self_pickup, pov_default, 
        prod_stock_out_sellable, prod_min_order_qty, pov_code, prod_max_order_qty, ' . Product::getPrice();
        $query = static::getListingObj($filters, $fields, false);
        $query->joinSavedProducts();
        $query->addSavedProductsCondition();
        $query->addCondition($id, 'sp.usp_id');
        return $query->firstRecord();
    }

    public static function getBrand($brandId)
    {
        return Brand::select('brand_id as id', 'brand_name as label')->where('brand_id', $brandId)->get()->toArray();
    }

    public function createdAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'prod_created_by_id')->select(['admin_id', 'admin_username']);
    }

    public function updatedAt()
    {
        return $this->hasOne('App\Models\Admin\Admin', 'admin_id', 'prod_updated_by_id')->select(['admin_id', 'admin_username']);
    }

    /**sendProductLowStockEmailToAdmin */
    public static function checkIfProductLowStock($productId)
    {
        $product = Product::isOutOfStock($productId);
        if (!empty($product)) {
            $admins = Admin::getAdminsByModule(AdminRole::PRODUCTS);
            $variantName = "";
            if (!empty($product->pov_display_title)) {
                $variantName  = '(' . str_replace('_', ' / ', $product->pov_display_title) . ')';
            }
            if (!empty($admins)) {
                foreach ($admins as $key => $admin) {
                    $notificationData = [];
                    $sendSms = false;

                    $data = EmailHelper::getEmailData('low_stock_notification_for_product');
                    $priority = $data['body']->etpl_priority;
                    $data['subject'] = Product::replaceProductTags($data['body']->etpl_subject, $product, $admin['admin_name'], $variantName);
                    $data['body'] = Product::replaceProductTags($data['body']->etpl_body, $product, $admin['admin_name'], $variantName);
                    $data['to'] = $admin['admin_email'];
                    $notificationData['email'] = $data;

                    $country = Country::select('country_phonecode')->where('country_id', $admin['admin_phone_country_id'])->first();
                    if (!empty($country->country_phonecode) && !empty($admin['admin_phone'])) {
                        $data = SmsTemplate::getSMSTemplate('low_stock_notification_for_product');
                        $priority = $data['body']->stpl_priority;
                        $data['body'] = str_replace('{name}', $admin['admin_name'], $data['body']->stpl_body);
                        $data['body'] = str_replace('{productName}', $product->prod_name . $variantName, $data['body']);
                        $notificationData['sms'] = [
                            'message' => $data['body'],
                            'recipients' => array('+' . $country->country_phonecode . $admin['admin_phone'])
                        ];
                        $sendSms = true;
                    }

                    dispatch(new SendNotification($notificationData, true, false, $sendSms))->onQueue($priority);
                }
            }
        }
    }

    private static function isOutOfStock($productId)
    {
        $query = Product::select('prod_id', 'prod_name', 'pov_display_title', 'pov_code')
        ->leftjoin('product_contents as pcontent', 'pcontent.pc_prod_id', 'products.prod_id');
        static::defaultVariant($query);
        $query->where('prod_id', $productId);
        $query->where('products.prod_published', Product::PRODUCT_PUBLISHED)
                ->whereDate('products.prod_publish_from', '<=', Carbon::today()->toDateString());
        $query->where('pc_threshold_stock_level', 1)
            ->whereRaw('pc_substract_stock >= coalesce(`pov_quantity`, `prod_stock`)');
        return $query->first();
    }
    
    private static function replaceProductTags($content, $product, $adminName, $variantName)
    {
        $content = str_replace('{name}', $adminName, $content);
        $content = str_replace('{productName}', $product->prod_name, $content);
        $content = str_replace('{productVariant}', $variantName, $content);
        $content = str_replace('{productImage}', productImageUrl($product->prod_id, $product->pov_code), $content);
        $content = str_replace('{productUrl}', getRewriteUrl("products", $product->prod_id), $content);
        $content = str_replace('{editProductUrl}', url('') . '/admin#/product/' . $product->prod_id . '/edit', $content);
        $content = str_replace('{websiteName}', (getConfigValueByName('BUSINESS_NAME') ?? ''), $content);
        return $content;
    }
    public static function getFavouritesForApp($userId, $page, $filters = "")
    {
        $fields =  'prod_id, prod_name, ufp_id, ' . self::getPrice() . ',' .
        DB::Raw("CONCAT('" . url('') . "', '/yokart/app/product/image/', prod_id, '/', IF(ufp_pov_code IS NULL, '0', pov_id), '?t=', UNIX_TIMESTAMP(prod_updated_on)) as prod_image") . ',' .
        DB::Raw("IF(ufp_pov_code IS NULL, '', ufp_pov_code) as pov_code") . ',' .
        DB::Raw("IF(pov_display_title IS NULL, '', pov_display_title) as pov_display_title");
        $query = static::getListingObj([], $fields, false);
        $query->includeFavouriteCount();
        $query->addCondition($userId, 'fav.ufp_user_id');
        
        if (!empty($filters)) {
            switch ($filters) {
                case 'latest':
                    $query->orderBy('ufp_id', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('ufp_id', 'ASC');
                    break;
                case 'best_selling':
                    $query->orderBy('prod_sold_count', 'DESC');
                    break;
                case 'most_discounted':
                    $query->orderBy('discount', 'Desc');
                    break;
                case 'alpha_asc':
                    $query->orderBy('prod_name', 'ASC');
                    break;
                case 'alpha_desc':
                    $query->orderBy('prod_name', 'DESC');
                    break;
                case 'price_desc':
                    $query->orderByFilterRaw('finalprice', 'DESC');
                    break;
                case 'price_asc':
                    $query->orderByFilterRaw('finalprice', 'ASC');
                    break;
            }
        }

        $query->addGroupByCondition('ufp_id');
        $query->setPageSize(config('app.app_pagination'));
        return $query->getRecordsbyPage($page);
    }
    
    public static function getListingForApp($fields, $filters, $page, $condition = true, $searchItem = '')
    {
        if (!empty($searchItem)) {
            $query = self::getListingSearchObject($filters, $fields, $searchItem);
        } else {
            $query = static::getListingObj($filters, $fields, $condition);
            $query->includeFavouriteCount();
        }
    
        if (!empty($filters['sortBy']) &&  $filters['sortBy'] != 'new') {
            $sortBy = $filters['sortBy'];
            if ($sortBy == 'priceDesc') {
                $query->orderByFilterRaw('finalprice', 'DESC');
            } elseif ($sortBy == 'priceAsc') {
                $query->orderByFilterRaw('finalprice', 'ASC');
            } elseif ($sortBy == 'popularity') {
                $query->orderBy('prod_sold_count', 'DESC');
            } elseif ($sortBy == 'rating') {
                $query->joinReviews();
                $query->addAdditionalFields('AVG(preview_rating) as avg_preview_rating');
                $query->orderBy('avg_preview_rating', 'DESC');
            } elseif ($sortBy == 'discounted') {
                $query->orderBy('discount', 'Desc');
            } elseif ($sortBy == 'alphabetically') {
                $query->orderBy('prod_name', 'ASC');
            }
        } else {
            $query->orderBy('prod_id', 'DESC');
        }
       
        $query->addGroupByCondition('prod_id');
        $query->setPageSize(config('app.app_pagination'));
  
        return $query->getRecordsbyPage($page);
    }
    public static function getListingByidsForApp($fields, $prodIds, $filters = [], $condition = true)
    {
        $query = static::getListingObj($filters, $fields, $condition);
        $query->includeFavouriteCount();
        $query->addCondition($prodIds);
        $query->orderByRaw('prod_id', $prodIds);
        $query->addGroupByCondition('prod_id');
        return $query->getRecords();
    }
    public static function updateReturnInventory($product, $qty, $requestId, $userId)
    {
        if (!empty($product->rewards)) {
            $rewardAmount = $product->rewards->opc_value;
            if ($product->op_qty != $qty) {
                $rewardAmount = round($rewardAmount / $qty);
            }
            UserRewardPoint::redeemRewardPoints($userId, $product->op_order_id, $rewardAmount, UserRewardPoint::ORDER_REQUEST_REFUND_POINT, $requestId);
        }
        $productId = $product->op_product_id;
        $varientCode = $product->op_pov_code;
        Product::where('prod_id', $productId)->decrement('prod_sold_count', $qty);
        if ($varientCode != "0") {
            ProductOptionVarient::where('pov_code', $varientCode)->increment('pov_quantity', $qty);
        } else {
            Product::where('prod_id', $product)->increment('prod_stock', $qty);
        }
    }

    public static function autoSuggestForApp($searchItem = '')
    {
        $fields = 'prod_id, prod_name, REPLACE(pov_display_title, "_","|") as variant_display_title, ' . self::getPrice();
        $query = self::getListingSearchObject([], $fields, $searchItem);
        $query->addProductImage();
        $query->addGroupByCondition('prod_id');
        $query->setlimit(5);
        return $query->getRecords();
    }
    public static function getOptionsFiltersForApp($filters, $condition, $searchItem)
    {
        $options = ProductOption::join('options', 'options.option_id', 'product_options.poption_option_id')->groupBy('option_id')->pluck('option_name', 'option_id');

        $products = [];
        foreach ($options as $opkey => $option) {
            $optionName = $option;
            if ($opkey == 1) {
                $optionName = "Color";
            } elseif ($opkey == 2) {
                $optionName = "Size";
            }
            $records = self::optionFiltersRecordsForApp($filters, $condition, $searchItem, $opkey);
            if (count($records) > 0) {
                $temp['id'] = $opkey;
                $temp['name'] = $optionName;
                $temp['values'] = $records;
                $products[] = $temp;
            }
        }

        return  $products;
    }
    public static function optionFiltersRecordsForApp($filters, $condition, $searchItem, $typeId)
    {
        $fields = 'opn_value, opn_id, opn_color_code, ' . Product::getPrice();
        $query = self::getListingObj($filters, $fields, $condition);
        $query->joinOptionToVarient();
        $query->addCondition($typeId, 'poption_option_id');
        $query->orderBy('opn_value', 'ASC');
        $query->addGroupByCondition('opn_id');
        return $query->getRecords()->toArray();
    }
    public static function getBlogProducts($fields, $blogId, $published = true)
    {
        $filters = [];
        $query = static::getListingObj($filters, $fields, true, [], $published);
        $query->joinBlogProducts();
        $query->joinUrlRewrite();
        $query->addCondition($blogId, 'ptbp_post_id');
        $query->addGroupByCondition('prod_id');
        return $query->getRecords();
    }
    public static function productByVarientId($prodId, $fields, $filters = [], $prodOptionId = 0, $published = false)
    {
        $conditions = true;
        if ($prodOptionId != 0) {
            $conditions = false;
        }
        $query = static::getListingObj($filters, $fields, $conditions, [], $published);
        $query->includeFavouriteCount();
        $query->joinUrlRewrite();
        $query->addCondition($prodId);
        if (!empty($prodOptionId)) {
            $query->addCondition($prodOptionId, 'pov_id');
        }
        return $query->firstRecord();
    }
}
