<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Collection;
use App\Models\ProductOption;
use App\Models\ProductOptionVarient;
use App\Models\ProductSpecification;
use App\Models\State;
use App\Models\Thread;
use App\Models\Search;
use App\Http\Controllers\YokartController;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;
use App\Helpers\ShippingHelper;
use Illuminate\Support\Arr;
use App\Models\ProductReview;

class ProductController extends YokartController
{
    public function __construct()
    { 
        parent::__construct();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->filterResult($request);
        }
        $filters = [];
        $catIds = [];
        $searchItem = '';
        $condition = true;

        if (!empty($request->get('s'))) {
            $searchItem = $request->get('s');
            $condition = false;
        }
        $fields = 'prod_id, prod_name,  pov_default, brand_name, brand_id, pov_display_title,
        prod_stock_out_sellable, prod_min_order_qty,cat_id ,cat_name, pov_code, pov_quantity,
         prod_max_order_qty,' . Product::getPrice();
        $products = Product::getListing($fields, $filters, 0, $condition, $searchItem);

        if (!empty($request->get('s'))) {
            Search::saveSearchTerm($request->get('s'), $products->count(), $request->ip());
        }

        $recentViewProducts = $this->getRecentViewProducts(0);

        return view('themes.' . config('theme') . '.product.listing', compact('products', 'catIds', 'recentViewProducts'));
    }

    public function collectionListing(Request $request, $pageId, $cId)
    {
        $collectionExists = Collection::where('collection_page_id', $pageId)
            ->where('collection_component_id', $cId)
            ->groupBy('collection_component_id')
            ->pluck('collection_layout')->count();
        $products = [];
        if ($collectionExists) {
            $filters = [
                'collection' => $pageId . "|" . $cId,
            ];
            $fields = 'prod_id, prod_name,  pov_default, brand_name, brand_id, pov_display_title,
                prod_stock_out_sellable, prod_min_order_qty,cat_id ,cat_name, pov_code, pov_quantity,
                prod_max_order_qty,' . Product::getPrice();
            $products = Product::getListing($fields, $filters);
        }
        $records['products'] = $products;
        $records['filterType'] = "collection";
        $records['filterValue'] = $pageId . "|" . $cId;
        $records['searchedBy'] = "collection";
        return view('themes.' . config('theme') . '.product.listing', $records);
    }


    public function detail($prodId, $subid = '',  Request $request = null)
    {
       
        if(is_numeric($prodId) == false){
            abort(404);
        }
       
        $fields = 'prod_id, prod_name, prod_price, prod_type,pc_weight_unit,pc_weight, pov_default, coalesce(`pov_quantity`, `prod_stock`) as totalstock,
        prod_stock_out_sellable, prod_min_order_qty, cat_name,cat_id, brand_id, brand_name, pov_code,prod_self_pickup, prod_cod_available,
         prod_max_order_qty,' . Product::getPrice();
        $published = true;
        if (!empty($this->admin_signed_in) && $this->admin_signed_in) {
            $published = false;
        }
        
        $code = 0;
        if(!empty($subid) && is_numeric($subid)) {
            $records = ProductOptionVarient::select('pov_code')->where('pov_id', $subid)->first();
            if ($records) {
                $code = $records->pov_code;
            }
        } elseif (!empty($subid)) {
            $code = $subid;
        }
        $product = Product::productById($prodId, $fields . ', pov_id,prod_description,prod_updated_on, pc_video_link,pc_warranty_age,pc_file_title, pc_return_age,pc_cancellation_age,  prod_model,pc_isbn, pc_hsn, pc_sac, pc_upc, coalesce(`pov_sku`, `pc_sku`) as sku, ' . Product::getPrice(), [], $code, $published);
       
        if (empty($product)) {
            abort(404);
        }
        
        $recentViewProducts = $this->getRecentViewProducts($prodId);
        $record['specifications'] = ProductSpecification::recordsByProductId($prodId);
        $record['buytogetherproducts'] = Product::getBuyTogetherProducts($fields, $prodId, $published);
        $record['relatedproducts'] = Product::getRelatedproducts($fields, $prodId, $published);
        $record['record_id'] = $prodId;
        $record['product'] = $product;
        $record['recentViewProducts'] = $recentViewProducts;

        $details = $this->detailsRecords($product);
        $subRecordId = productColorId($product);

        $record['productImages'] = getProductImages($prodId, $subRecordId, true);
        $record['allVarients'] = $details['allVarients'];
        $record['rating'] = $details['rating'];
        $record['sizeExist'] = $details['sizeExist'];
        $record['page'] = 1;
        $record['published'] = $published;
        $record['shipping'] = true;

        if (!empty($_GET['review'])) {
            $position = ProductReview::where('preview_prod_id', $prodId)->where('preview_id', '>=', $_GET['review'])->count();
            $record['page'] = (int) ceil($position / 5);
        }
        return view('themes.' . config('theme') . '.product.detail', $record);
    }
    private function detailsRecords($product)
    {
        $record['rating'] = ProductReview::getAverageRatingByProductId($product->prod_id);
        $record['allVarients'] = [];
        if (!empty($product->prod_id) && $product->pov_code != "") {
            $record['allVarients'] = ProductOptionVarient::getActiveRecordsByProductId($product->prod_id);
        }
        $record['sizeExist'] = getFileUrl('productChartUpload', $product->prod_id) ? true : false;
        return $record;
    }
    public function getImages($prodId, $subRecordId)
    {
        $productImages = getProductImages($prodId, $subRecordId, true);
        return view('themes.' . config('theme') . '.product.partials.sliderImages', compact('productImages'))->render();
    }
    public function getGalleryImages(Request $request)
    {
        $prodId = $request->input('product-id');
        $varientId = $request->input('variant-id');
        $prodName = $request->input('prod-name');


        $images = getProductImages($prodId, $varientId, true);
        return view('themes.' . config('theme') . '.partials.productCardGallery', compact('images', 'prodName'))->render();
    }
    public function getRecentViewProducts($prodId)
    {
        $cookieArray = [];
        $getCookie = $this->retrieveCookie('RecentlyViewedProds');
        $productids = unserialize($getCookie);

        if (!empty($productids) && ($key = array_search($prodId, $productids)) !== false) {
            unset($productids[$key]);
        }
        if(!empty($productids)) {
            $cookieArray = array_reverse($productids);
        }
        if ($cookieArray) {
            if (count($cookieArray) == 10) {
                array_shift($cookieArray);
            }
            array_push($cookieArray, $prodId);
            $cookies = $cookieArray;
        } else {
            $cookies =  [$prodId];
        }
        $this->makeCookie('RecentlyViewedProds', serialize($cookies));
        $cookieArray = $productids;
        $recentViewProducts = [];
        if (!empty($productids)) {
            $recentViewProducts = Product::getDataByIds($productids);
        }
        return $recentViewProducts;
    }
    public function varientPrice(Request $request)
    {
        $productId = $request->input('product-id');
        $selectedCode = $request->input('varientCode');
        $published = true;
        if (!empty($this->admin_signed_in) && $this->admin_signed_in) {
            $published = false;
        }

        $fields = 'prod_id, prod_name, pov_id, prod_price, prod_type, pc_weight_unit, pc_weight, pov_default,
        prod_stock_out_sellable, prod_min_order_qty, brand_id, brand_name, prod_model, coalesce(`pov_sku`, `pc_sku`) as sku, pov_code,prod_self_pickup, prod_cod_available,
         prod_max_order_qty,pc_warranty_age,pc_return_age, pc_file_title, pc_cancellation_age, ' . Product::getPrice();

        $product = Product::productById($productId, $fields, [], $selectedCode, $published);

        $details = $this->detailsRecords($product);

        $record['sizeExist'] = $details['sizeExist'];
        $record['product'] = $product;
        $record['allVarients'] = $details['allVarients'];
        $record['rating'] = $details['rating'];
        $record['shipping'] = true;

        $subRecordId = productColorId($product);
  

        $sliderHtml = '';
        if ($subRecordId != 0) {
            $sliderHtml =  $this->getImages($productId, $subRecordId, true);
       }
        $record['published'] = $published;

        $addToCartHtml = view('themes.' . config('theme') . '.product.partials.addToCartSection', $record)->render();
        return jsonresponse(true, null, ['sliderHtml' => $sliderHtml, 'addToCartHtml' => $addToCartHtml]);
    }
    public function validateShippingLocation(Request $request)
    {
        $request->validate([
            'pincode' => 'required'
        ]);
        $response = \GoogleMaps::load('geocoding')->setParam(['address' => $request['pincode']])
            ->get('results.address_components');
        $stateCode = '';
        if (count($response) > 0 && count($response['results']) > 0) {
            foreach ($response['results'][0]['address_components'] as $fields) {
                if ($fields['types'][0] == 'administrative_area_level_1') {
                    $stateCode = $fields['short_name'];
                    break;
                }
            }
        }
        if ($stateCode) {
            $state = State::getRecordByCode($stateCode);
            $shipping = new ShippingHelper($state->state_country_id, $state->state_id);
            $varientCode = '';
            if (!empty($request->input('varient-code'))) {
                $varientCode = str_replace(',', '|', $request->input('varient-code')) . '|';
            }
            $fields = 'prod_id, prod_name, prod_price, prod_type, pc_weight_unit, pc_weight, pov_default,
            prod_stock_out_sellable, prod_min_order_qty, brand_id, brand_name, prod_model, coalesce(`pov_sku`, `pc_sku`) as sku, pov_code,prod_self_pickup, prod_cod_available,
             prod_max_order_qty,pc_warranty_age,pc_return_age, pc_file_title, pc_cancellation_age, ' . Product::getPrice();
            $product = Product::productById($request['prod-id'], $fields, [], $varientCode);
            $shippingResults = $shipping->getShipmentByProduct($product);
            if (!empty($shippingResults) && count($shippingResults) > 0) {
                $record['shipping'] = true;
            } else {
                $record['shipping'] = false;
            }
            $record['product'] = $product;
            $details = $this->detailsRecords($product);

            $record['options'] = $details['options'];
            $record['allVarients'] = $details['allVarients'];
            $record['rating'] = $details['rating'];
            $record['pincode'] = $request['pincode'];
            $published = true;
            if (!empty($this->admin_signed_in) && $this->admin_signed_in) {
                $published = false;
            }

            $record['published'] = $published;
            $addToCartHtml = view('themes.' . config('theme') . '.product.partials.addToCartSection', $record)->render();
            return jsonresponse(true, null, ['addToCartHtml' => $addToCartHtml]);
        }
        return jsonresponse(false, __('NOTI_PIN_CODE_INCORRECT'));
    }
    public function filters(Request $request)
    {
        $results = $request->all();
        $filters = $results;

        $catListView = false;
        if (isset($filters['searchedBy']) && $request["search-items"] != "" && ($filters['searchedBy'] == "collection" || $filters['searchedBy'] == "brands")) {
            $catListView = true;
        }
        $condition = false;

        $fields = 'prod_condition, brand_id as value, brand_name as label, ' . Product::getPrice();
        $products = Product::getAllProducts($filters, $fields, $condition, $request["search-items"]);

        $results['minPrice'] = round(displayPrice($products->min('finalprice'), false, false));
        $results['maxPrice'] =  round(displayPrice($products->max('finalprice'), false, false));

        $results['options'] = Product::getOptionsFilters($filters, $condition, $request["search-items"]);

        $brands = Product::getBrandFilters($filters, true, $condition, $request["search-items"]);

        $results['brandRecords'] = [
            'loadMore' => $brands->total() > 6 ? true : false,
            'list' =>  $brands->pluck('label', 'value'),
        ];
        $results['conditions'] = $products->whereNotNUll('prod_condition')->pluck('prod_condition', 'prod_condition')->toArray();
        $results['allConditions'] = Product::PRODUCT_CONDITIONS;
        $results['categoryRecords'] =  Product::getCategoryFilters($filters, $condition, $request["search-items"], $catListView);

        $results['catListView'] = $catListView;

        return view('themes.' . config('theme') . '.product.partials.filters', $results);
    }

    public function filterResult(Request $request)
    {
        

        $filters = $request->all();
        if ($request->input('appliedFilters')) {
            $filters = json_decode($request->input('appliedFilters'), true);
        }
        $keywordSearch = false;
        $condition = true;
        if (!empty($filters['options']) || $request["search-items"] != "") {
            $condition = false;
        }
       
        $fields = 'prod_id, prod_name,  pov_default, brand_name, brand_id, pov_display_title,
        prod_stock_out_sellable, prod_min_order_qty,cat_id ,cat_name, pov_code, pov_quantity,
         prod_max_order_qty,' . Product::getPrice();
        
        $products = Product::getListing($fields, $filters, $request->input('showRecords'), $condition, $request["search-items"]);
      
        if ($request->input('moreFilters') == "true") {
           
            $type = $request['search-id'];
            $selectedFilters = [];
            if ($request['brands'] && $request['last-filters'] === 'brands') {
                if (!empty($request['brands'])) {
                    $selectedFilters = $request['brands'];
                }

                $brandRecords = Product::getBrandFilters($filters, true, $condition, $request["search-items"], $selectedFilters);

                $results['brandRecords'] = [
                    'loadMore' => $brandRecords->total() > 6 ? true : false,
                    'list' => $brandRecords->pluck('label', 'value'),
                ];
                $results['selectedFilters'] = $selectedFilters;
                $html['brandFilters'] = view('themes.' . config('theme') . '.product.partials.brandWidgetFilters', $results)->render();
            } elseif ($request['options'] && (!empty($request['last-filters']) && $request['last-filters'] != 'brands')) {
                $key = $request['last-filters'];
                if (!empty($request['options'])) {
                    $selectedFilters = Arr::flatten($request['options']);
                }

                $option = Product::optionFiltersRecords($filters, false, $request["search-items"], $type, $selectedFilters, true);
                $option['name'] = $key;
                $html[$key . 'Listing'] = view('themes.' . config('theme') . '.product.partials.optionsWidgetFilters', ['option' => $option, 'selectedFilters' => $selectedFilters, 'key' => $type])->render();
            }
        }
        if ($request["search-items"] != "") {
            $keywordSearch = true;
        }

        $html['productSection'] = view('themes.' . config('theme') . '.product.partials.productGrid', compact('products', 'filters', 'keywordSearch'))->render();
        return $html;
    }

    public function askQuestions(Request $request, $productId, $varientCode)
    {
       
        if ($this->signed_in) {
            return jsonresponse(true, '', view('themes.' . config('theme') . '.partials.askQuestionPopup', compact('productId', 'varientCode'))->render());
        } else {
            return jsonresponse(true, '', loginPopup());
        }
    }

    public function submitQuestion(AskQuestionRequest $request)
    {
        $records = $request->all();
        $product = Product::select('prod_name')->where('prod_id', $request->input('thread_product_id'))->first();
        $records['thread_product_name'] = $product->prod_name;

        if (Thread::createMessageThread($records, $this->user->user_id)) {
            return jsonresponse(true, __('LBL_QUESTION_SUBMITTED'));
        }
    }

    public function loadFilters(Request $request, $type)
    {
        $condition = true;
        if ($request["search-items"] != "") {
            $condition = false;
        }

        $searchedId = $request['search-id'];
        $filters = $request->all();
        $results = [];
        $colors = [];

        switch ($type) {
           
            case 'brands':
                if (isset($filters['options'])) {
                    unset($filters['options']);
                } 
                if (isset($filters['brands'])) {
                    unset($filters['brands']);
                } 
                $records = Product::getBrandFilters($filters, false, $condition, $request["search-items"])->pluck('value', 'label');
                $type = 'brands';
                $filters = [];
                if (isset($request['brands'])) {
                    $filters = $request[$type];
                }
                break;
            case 'options':
                $type =  $request['search-type'];

                if ((isset($filters['brands']) && isset($filters['searchedBy']) && $filters['searchedBy'] != "brands") || empty($filters['searchedBy'])) {
                    unset($filters['brands']);
                }

                $records = Product::getOptionsFiltersByType($filters, $condition, $searchedId, $request["search-items"]);

                if ($searchedId == 1) {
                    $colors = $records->pluck('opn_color_code', 'label');
                }
                $records = $records->pluck('value', 'label');
                $filters = [];
                if (isset($request['options'])) {
                    $filters = Arr::flatten($request['options']);
                }
                break;
        }


        $html = view('themes.' . config('theme') . '.product.partials.loadMoreFilters', compact('records', 'filters', 'type', 'searchedId', 'colors'))->render();
        return jsonresponse(true, null, $html);
    }
}
