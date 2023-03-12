<?php

namespace App\Helpers;

use App\Helpers\YokartHelper;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Collection;
use App\Models\SpecialPrice;
use App\Models\OptionToVarient;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class ProductSearchHelper
{
    protected $productCondition;
    protected $pagination;
    protected $query;
    protected $pageSize;
    protected $usedPageSize;

    /**
     * our object constructor
     *
     * @param $activeProduct
     * @param $productCondition
     */
    public function __construct($fields = [], $published = true)
    {
        if (is_array($fields)) {
            $this->query = Product::select($fields);
        } else {
            $this->query = Product::selectRaw($fields);
        }
        $this->pageSize = getConfigValueByName('LISTING_RECORDS_PER_PAGE');
        $this->usedPageSize = false;
        if ($published == true) {
            $this->query->where('products.prod_published', Product::PRODUCT_PUBLISHED)
                ->whereDate('products.prod_publish_from', '<=', Carbon::today()->toDateString());
        }
    }

    public function addAdditionalFields($fields)
    {
        $this->query->addSelect(DB::Raw($fields));
    }
    public function joinReviews()
    {
        $this->query->leftJoin('product_reviews as preview', function ($join) {
            $join->on('preview.preview_prod_id', 'products.prod_id')
                ->where('preview_status', 2);
        });
    }
    public function joinProductToCategory()
    {
        $this->query->leftjoin('product_to_categories as ptc', function ($catQuery) {
            $catQuery->on('ptc.ptc_prod_id', 'products.prod_id')
                ->join('product_categories as pc', 'ptc.ptc_cat_id', 'pc.cat_id')
                ->where('pc.cat_publish', config('constants.YES'));
        });
    }
    public function joinProductSepcialPrice()
    {
        $currentDateTime = Carbon::now()->toDateTimeString();
        $dbprefix = \DB::getTablePrefix();

        $this->query->leftjoin('special_price_includes as psp', function ($pspQuery) use ($currentDateTime, $dbprefix) {
            $pspQuery->on('psp.spi_record_id', 'products.prod_id')
                ->join('special_prices as prodsp', 'prodsp.splprice_id', 'psp.spi_splprice_id')
                ->where(['prodsp.splprice_include' => SpecialPrice::PRODUCT_INCLUDE, 'prodsp.splprice_publish' => config('constants.YES')])
                ->whereRaw('(spi_subrecord_id = 0 or spi_subrecord_id = pov_code)')
                ->whereRaw($dbprefix . 'prodsp.splprice_startdate <= "' . $currentDateTime . '"')
                ->whereRaw($dbprefix . 'prodsp.splprice_enddate >= "' . $currentDateTime . '"');
        });
    }
    public function joinExcludeSepcialPrice()
    {
        $this->query->leftjoin('special_price_excludes as spe', function ($sql) {
            $sql->on('spe.spe_record_id', 'products.prod_id');
        });
    }
    public function addProductImage()
    {
        //$this->query->addSelect(DB::Raw("CONCAT('" . url('') . "', '/yokart/app/product/image/', prod_id, '/', IF(pov_code IS NULL, '0', pov_id), '/" . getProdSize('medium') . "', '?t=', '" . time() . "') as prod_image"));
        $this->query->addSelect(DB::Raw("CONCAT('" . url('') . "', '/yokart/app/product/image/', prod_id, '/', IF(pov_code IS NULL, '0', pov_id), '?t=', UNIX_TIMESTAMP(prod_updated_on)) as prod_image"));
    }
    public function joinBrandSepcialPrice()
    {
        $currentDateTime = $dt = Carbon::now()->toDateTimeString();
        $dbprefix = \DB::getTablePrefix();

        $this->query->leftjoin('special_price_includes as bsp', function ($pspQuery) use ($currentDateTime, $dbprefix) {
            $pspQuery->on('bsp.spi_record_id', 'brands.brand_id')
                ->join('special_prices as brandsp', 'brandsp.splprice_id', 'bsp.spi_splprice_id')
                ->leftjoin('special_price_excludes as bspe', function ($brand_ex_sp) {
                    $brand_ex_sp->on('brandsp.splprice_id', 'bspe.spe_splprice_id');
                })
                ->whereRaw('(' . $dbprefix . 'bspe.spe_splprice_id IS NULL OR ' . $dbprefix . 'ptc.ptc_prod_id != ' . $dbprefix . 'bspe.spe_record_id or (' . $dbprefix . 'ptc.ptc_prod_id = ' . $dbprefix . 'bspe.spe_record_id and (' . $dbprefix . 'bspe.spe_subrecord_id != 0 and ' . $dbprefix . 'varients.pov_code  NOT IN (select spe_subrecord_id from ' . $dbprefix . 'special_price_excludes where spe_record_id = ' . $dbprefix . 'ptc.ptc_prod_id))))')
                ->where(['brandsp.splprice_include' => SpecialPrice::BRAND_INCLUDE, 'brandsp.splprice_publish' => config('constants.YES')])
                ->whereRaw($dbprefix . 'brandsp.splprice_startdate <= "' . $currentDateTime . '"')
                ->whereRaw($dbprefix . 'brandsp.splprice_enddate >= "' . $currentDateTime . '"');
        });
    }
    public function joinCategorySepcialPrice()
    {
        $currentDateTime = $dt = Carbon::now()->toDateTimeString();
        $dbprefix = \DB::getTablePrefix();

        $this->query->leftjoin('special_price_includes as csp', function ($pspQuery) use ($currentDateTime, $dbprefix) {
            $pspQuery->on('csp.spi_record_id', 'pc.cat_id')
                ->join('special_prices as catsp', 'catsp.splprice_id', 'csp.spi_splprice_id')
                ->leftjoin('special_price_excludes as cspe', function ($cat_ex_sp) {
                    $cat_ex_sp->on('catsp.splprice_id', 'cspe.spe_splprice_id');
                })
                ->whereRaw('(' . $dbprefix . 'cspe.spe_splprice_id IS NULL OR ' . $dbprefix . 'ptc.ptc_prod_id != ' . $dbprefix . 'cspe.spe_record_id or (' . $dbprefix . 'ptc.ptc_prod_id = ' . $dbprefix . 'cspe.spe_record_id and (' . $dbprefix . 'cspe.spe_subrecord_id != 0 and ' . $dbprefix . 'varients.pov_code NOT IN (select spe_subrecord_id from ' . $dbprefix . 'special_price_excludes where spe_record_id =  ' . $dbprefix . 'ptc.ptc_prod_id))))')
                ->where(['catsp.splprice_include' => SpecialPrice::CATEGORY_INCLUDE, 'catsp.splprice_publish' => config('constants.YES')])
                ->whereRaw($dbprefix . 'catsp.splprice_startdate <="' . $currentDateTime . '" and ' . $dbprefix . 'catsp.splprice_enddate >="' . $currentDateTime . '" and (' . $dbprefix . 'catsp.splprice_id != ' . $dbprefix . 'csp.spi_record_id)');
        });
    }
    public function joinCategory()
    {
        $this->query->join('product_categories as pc', 'product_to_categories.ptc_cat_id', 'pc.cat_id');
    }
    

    public function joinOptions()
    {
        $this->query->leftJoin('product_options as po', function ($sql) {
            $sql->on('po.poption_prod_id', 'products.prod_id')
                ->join('product_option_names as pname', 'opn_id', 'poption_opn_id');
        });
    }
    public function joinOptionToVarient()
    {

        $this->query->leftjoin('option_to_varients as otv', function ($catQuery) {
            $catQuery->on('otv.otv_pov_id', 'varients.pov_id')
                ->join('product_options as option', 'otv.otv_opn_id', 'option.poption_opn_id')
                ->join('product_option_names as opname', 'opname.opn_id', 'option.poption_opn_id');
        });
    }
    public function joinContent()
    {
        $this->query->leftjoin('product_contents as pcontent', 'pcontent.pc_prod_id', 'products.prod_id');
    }
    public function joinOptionNames()
    {
        $this->query->join('options', 'options.option_id', 'po.poption_option_id');
    }
    public function joinBrand()
    {
        $this->query->leftJoin('brands', function ($bSql) {
            $bSql->on('products.prod_brand_id', 'brands.brand_id')->where('brands.brand_publish', config('constants.YES'));
        });
    }
    public function joinRelatedProducts()
    {
        $this->query->join('related_products as related', 'related.related_recommend_product_id', 'products.prod_id');
    }

    public function joinBuyTogetherProducts()
    {
        $this->query->join('buy_together_products as btp', 'btp.btp_recommend_product_id', 'products.prod_id');
    }
    public function joinProductCollection()
    {
        $this->query->join('collections', 'collections.collection_record_subid', 'products.prod_id');
    }
    public function joinCategoryCollection()
    {
        $this->query->with('CategoryCollection');
    }
    public function joinUrlRewrite()
    {
        $this->query->with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom');
    }
    public function includeFavouriteCount()
    {
        $userId = 0;
        if (Auth::check()) {
            $userId = Auth::user()->user_id;
        } else if (Auth::guard('api')->check()) {
            $userId = Auth::guard('api')->user()->user_id;
        }
        $this->query->leftjoin('user_favourite_products as fav', function ($join) use ($userId) {
            $join->on('fav.ufp_prod_id', '=', 'products.prod_id')
                ->where('ufp_user_id', $userId)
                ->whereRaw('(ufp_pov_code = pov_code or  ufp_pov_code IS NULL)');
        })->addSelect('ufp_id as favId', 'ufp_pov_code');
    }
    public function joinVarient($default = true, $searchOptions = [], $type = '', $publish = true)
    {
        $this->query->leftjoin('product_option_varients as varients', function ($sql) use ($default, $searchOptions, $type, $publish) {
            $sql->on('prod_id', '=', 'varients.pov_prod_id');

            if (count($searchOptions) > 0) {
                $options = cartesianArray($searchOptions);
                foreach ($options as $option) {
                    $varient = implode('|', $option);
                    $sql->Where('pov_code', 'LIKE', '%|' . $varient . '|%');
                }
            }
            if ($publish == true) {
                $sql->where('varients.pov_publish', '=', config('constants.YES'));
            }
            if ($default == true) {
                $sql->where('varients.pov_default', '=', config('constants.YES'));
            }
        });
    }
    public function orderByFilterRaw($fieldName, $type)
    {
        return $this->query->orderByRaw('CONVERT(' . $fieldName . ', SIGNED)' . $type);
  /*  } elseif ($sortBy == 'priceAsc') { 
        $query->orderByFilterRaw('CONVERT(finalprice, SIGNED), ASC');
        return $this->query->orderBy($fieldName, $type);*/
    }
    public function joinColourOption()
    {
        $this->query->with('colorOptions:poption_prod_id,pov_prod_id,poption_id,poption_opn_id,opn_color_code,opn_value');
    }
    public function getLastUpdatedImage()
    {
        $this->query->with('getUpdatedImage:afile_record_id,afile_updated_at');
    }
    public function getRecordsbyPage($page)
    {
        return $this->query->paginate((int) $this->pageSize, ['*'], 'page', $page);
    }
    public function joinProductContent()
    {
        $this->query->join('product_contents', 'product_contents.pc_prod_id', 'products.prod_id');
    }
    public function joinTax()
    {
        $this->query->leftjoin('tax_groups as tgrp', 'tgrp.taxgrp_id', 'products.prod_taxcat_id');
    }
    public function joinSavedProducts()
    {
        $this->query->join('user_saved_products as sp', 'sp.usp_prod_id', 'products.prod_id');
    }
    public function addCondition($ids = [], $fieldName = 'prod_id')
    {
        if (is_array($ids) && count($ids) > 0) {
            $this->query->whereIn($fieldName, $ids);
        } else {
            $this->query->where($fieldName, $ids);
        }
    }
    public function addSearchCondition($value, $escapeWords = [], $options = [])
    {
        $valueArray = explode(' ', $value);
        $this->query->where(function ($query) use ($valueArray, $escapeWords, $value, $options) {
            $value  = cleanString($value);
            $query->orWhereRaw("REPLACE(`brand_name`, ' ', '') LIKE ?", [$value])
                ->orWhereRaw("REPLACE(`prod_name`, ' ', '') LIKE ?", ['%' . $value . '%'])
                ->orWhereRaw("REPLACE(`cat_name`, ' ', '') LIKE ?", ['%' . $value . '%'])
                ->orWhere('pc_isbn', 'like', $value)
                ->orWhere('pc_hsn', 'like', $value)
                ->orWhere('pc_sac', 'like', $value)
                ->orWhere('opn_value', 'like', $value)
                ->orWhere('pc_upc', 'like', $value);
            if (count($valueArray) > 1) {
                foreach ($valueArray as $keyword) {
                    if (!in_array($keyword, $escapeWords)) {
                        $keyword  = cleanString($keyword);
                        $query->orWhereRaw("REPLACE(`brand_name`, ' ', '') LIKE ?", [$value])
                            ->orWhereRaw("REPLACE(`cat_name`, ' ', '') LIKE ?", ['%' . $value . '%'])
                            ->orWhereRaw("REPLACE(`opn_value`, ' ', '') LIKE ?", ['%' . $keyword . '%'])
                            ->orWhereRaw("REPLACE(`prod_name`, ' ', '') LIKE ?", ['%' . $keyword . '%']);
                    }
                }
            }
        });
    }

    public function addBrandCondition($brand = [])
    {
        if (is_array($brand) && count($brand) > 0) {
            $this->query->whereIn('prod_brand_id', $brand);
        } else {
            $this->query->where('prod_brand_id', $brand);
        }
    }
    public function addProdCondition($condition = [])
    {
        if (is_array($condition) && count($condition) > 0) {
            $this->query->whereIn('prod_condition', $condition);
        } else {
            $this->query->where('prod_condition', $condition);
        }
        $this->query->where('prod_type', Product::PHYSICAL_PRODUCT);
    }
    public function addGroupByCondition($condition)
    {
        $this->query->groupBy($condition);
    }
    public function addCategoryCondition($category = [])
    {
        if (is_array($category) && count($category) > 0) {
            $this->query->whereIn('ptc.ptc_cat_id', $category);
        } else {
            $this->query->where('ptc.ptc_cat_id', $category);
        }
    }
    public function addOptionCondition($options)
    {
       if(count($options) > 1) {
            $options = cartesianArray(array_values($options));
       }


        $this->query->join('option_to_varients', function ($opSql) use ($options) {
             $opSql->on('otv_pov_id', 'pov_id');
                foreach ($options as $option) {
                    if (count($options) > 1) {
                        sort($option);
                        $varient = implode('|', $option);
                        $opSql->orWhere('pov_code', 'LIKE', '%|' . $varient . '|%');
                    } else {
                        $opSql->WhereIN('otv_opn_id', $option);
                    }
                    $opSql->groupBy('pov_prod_id');
                }
        });
      
    }
    public function addSavedProductCondition($type, $sesstion)
    {
        $this->query->where(function ($q) use ($type, $sesstion) {
            $q->where('usp_temp', $type);
            if ($type == 1) {
                $q->orWhere('usp_session_id', $sesstion);
            }
        });
    }
    public function addCouponSearchCondition($searchTerms)
    {
        $this->query->where(function ($q) use ($searchTerms) {
            foreach ($searchTerms as $searchKey => $searchTerm) {
                foreach ($searchTerm as $searchValue) {
                    $q->orWhere($searchKey, $searchValue);
                }
            }
        });
    }
    public function addSearchFieldCondition($fieldName, $searchTerm)
    {
        $this->query->WhereRaw("REPLACE($fieldName, ' ', '') LIKE ?", ['%' . $searchTerm . '%']);
    }
    public function addNotInCondition($fieldName, $ids)
    {
        $this->query->whereNotIn($fieldName, $ids);
    }
    public function addPriceRangeCondition($price)
    {
        $priceArray = explode(',', $price);
        $minFilter = (int) $priceArray[0];
        $maxFilter = (int) $priceArray[1];
     //   echo 'finalprice >= "' . convertDefaultCurrency($minFilter) . '" and finalprice <= "' . convertDefaultCurrency($maxFilter) . '"';exit;
     //echo 'finalprice >= ' . convertDefaultCurrency($minFilter) . ' and finalprice <= ' . convertDefaultCurrency($maxFilter);exit;
        $this->query->havingRaw('finalprice >= ' . convertDefaultCurrency($minFilter) . ' and finalprice <= ' . convertDefaultCurrency($maxFilter) );
    }


    public function addSavedProductsCondition()
    {
        $this->query->whereRaw('prod_id = usp_prod_id and (usp_pov_code = pov_code OR usp_pov_code IS NULL OR usp_pov_code = "")');
    }
    public function emptyConditions()
    {
        $productType = getConfigValueByName('PRODUCT_TYPES');

        if ($productType == 1) {
            $this->query->whereRaw('prod_from_origin_country_id != "" && pc_weight_unit != "" && prod_sbpkg_id != "" && pc_weight !=""');
        }
        $this->query->whereRaw('coalesce(pov_price, prod_price) != ""  && coalesce(`pov_quantity`, `prod_stock`) != ""');
        if ($productType != 3) {
            $this->query->where('prod_type', $productType);
        }
        $this->query->whereNotNull('tgrp.taxgrp_id');
    }

    public function addProductQuantityCondition()
    {
        return $this->query->where('productQuantity', '<=', '5');
    }

    public function having()
    {
        return $this->query->distinct('prod_id');
    }
    public function setPageSize($records = 0)
    {
        if ((int) $records > 0) {
            $this->pageSize = (int) $records;
        }
        $this->usedPageSize = true;
    }
    public function firstRecord()
    {
        return $this->query->first();
    }
    public function orderBy($fieldName, $type)
    {
        return $this->query->orderBy($fieldName, $type);
    }
    public function orderByRaw($fieldName, $productIds)
    {
        $orderByIds = implode(',', $productIds);
        return $this->query->orderByRaw("IF(FIELD($fieldName, $orderByIds)=0,1,0),FIELD($fieldName, $orderByIds)");
    }
    public function displaySql()
    {
        return $this->query->toSql();
    }

    public function getRecords()
    {
        if ($this->usedPageSize == true) {
            $pagination = $this->query->paginate((int) $this->pageSize);
            return $pagination->withPath(route('productListing'));
        }
        return $this->query->get();
    }

    public function joinCountry()
    {
        $this->query->leftJoin('countries', 'products.prod_from_origin_country_id', 'countries.country_id');
    }


    public function joinShippingPackages()
    {
        $this->query->leftJoin('shipping_box_packages', 'products.prod_sbpkg_id', 'shipping_box_packages.sbpkg_id');
    }

    public function getCount()
    {
        return $this->query->count();
    }
    public function setArray()
    {
        return $this->query->toArray();
    }

    public function getTake($size)
    {
        $this->query->take($size);
    }

    public function getSkip($skip)
    {
        $this->query->skip($skip);
    }
    public function setlimit($limit)
    {
        $this->query->limit($limit);
    }

    public function addConditionWithOperator($fieldName, $operator, $value)
    {
        return $this->query->where($fieldName, $operator, $value);
    }
    public function joinProductCollections($pageId, $componentId, $sort)
    {
      
        $layout = Collection::where('collection_page_id', $pageId)
            ->where('collection_component_id', $componentId)
            ->groupBy('collection_component_id')
            ->pluck('collection_layout')->first();
        
            
        if ($layout == 2) {
            $productIds = Collection::getRecordIdsByCid($pageId, $componentId);
            $this->addCondition($productIds);
            if ($sort == false) {
                $this->orderByRaw('prod_id', $productIds);
            }
        } elseif ($layout == 3) {
            $categoryIds = Collection::where('collection_page_id', $pageId)
                ->where('collection_component_id', $componentId)->groupBy('collection_record_id')
                ->oldest('collection_id')->pluck('collection_record_id')->toArray();
            $productIds = Collection::getRecordSubIds($pageId, $componentId, $layout, $categoryIds);
            $this->addCondition($categoryIds, 'ptc_cat_id');
            if (!empty($productIds) && count($productIds) > 0) {
                if ($sort == false) {
                    $orderByIds = implode(',', $productIds);
                    $this->orderByRaw('prod_id', $productIds);
                }
            }
        }
    }
}
