<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\ProductReview;
use App\Models\ProductReviewHelpful;
use App\Models\ProductOptionVarient;
use App\Models\Thread;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\Search;
use App\Models\RecentlyViewedProduct;
use DB;
use Auth;

class ProductController extends YokartController
{
    public function removeSearchTerm(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $response = [];
            if ($request->input('remove_all') == 1) {
                Search::where('search_by', Auth::guard('api')->user()->user_id)->delete();
                $searches = [];
            } else {
                Search::where('search_term', $request->input('search'))->where('search_by', Auth::guard('api')->user()->user_id)->delete();
                $searches = Search::getRecentSearches(Auth::guard('api')->user()->user_id)->toArray();
            }
            
            $response['searches'] = $searches;
            return apiresponse(config('constants.SUCCESS'), '', $response);
        } else {
            return apiresponse(config('constants.ERROR'));
        }
    }
    public function autosuggest(Request $request)
    {
        $response = [];
        $products = Product::autoSuggestForApp($request->get('search'));
        $productCategories = ProductCategory::searchForApp($request->get('search'));
        $response['products'] = $products->toArray();
        $response['categories'] = $productCategories->toArray();
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    public function defaultSearchResults(Request $request)
    {
        $response = [];
        $searches = [];
        $favRecords = [];
        $recentViewProducts = [];
        $userId = "";
        if (Auth::guard('api')->check()) {
            $userId = Auth::guard('api')->user()->user_id;
            $favourites = Product::getFavouritesForApp($userId, $request->input('page_no'), []);
            if (count($favourites) > 0) {
                $favRecords = $favourites->toArray()['data'];
            }
            if ($request->input('fav') != 1) {
                $searches = Search::getRecentSearches($userId)->toArray();
                $recentViewProducts = RecentlyViewedProduct::getRecentlyViewedProducts(0, 0, $userId);
            }
        }
        $response['viewed_prods'] = $recentViewProducts;
        $response['searches'] = $searches;
        $response['favourites'] = $favRecords;
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    public function list(Request $request, $page)
    { 
        $response = [];

        $filters = [];
        $searchItem = '';
        $condition = true;

        $categories = $request->input("cat_id");
        $brands = $request->input("brand_id");
        $products = $request->input("product_id");
        $options = $request->get('options');
        $price = $request->get('price');
        $conditions = $request->get('conditions');
        $sortBy = $request->get('sort_by');
        if (!empty($categories)) {
            $filters['categories'] = $categories;
        }
        if (!empty($brands)) {
            $filters['brands'] = $brands;
        }
        if (!empty($products)) {
            $filters['products'] = $products;
        }
        if (!empty($options)) {
            $filters['options'] = $options;
            $condition = false;
        }
        if (!empty($price) && (!empty($price['min']) || $price['min'] == 0)  && (!empty($price['max'])  || $price['min'] == 0)) {
            $filters['price-range'] = $price['min'] . "," . $price['max'];
        }
        if (!empty($conditions)) {
            $filters['conditions'] = $conditions;
        }

      
        if (!empty($request->get('search'))) {
            $searchItem = $request->get('search');
            $condition = false;
        }
        if (!empty($sortBy)) {
            $filters['sortBy'] = $sortBy;

        }
      
        $fields = 'prod_id, prod_name,  pov_default, brand_name, brand_id, pov_display_title,
        prod_stock_out_sellable, prod_min_order_qty,cat_id ,cat_name, pov_code, pov_id, pov_quantity,
         prod_max_order_qty,' . Product::getPrice();
        $products = Product::getListingForApp($fields, $filters, $page, $condition, $searchItem);
        
        if (!empty($request->get('search'))) {
            $user_id = Auth::guard('api')->check() ? Auth::guard('api')->user()->user_id : 0;
            Search::saveSearchTerm($request->get('search'), $products->count(), $request->ip(), Search::SEARCH_FROM_APP, $user_id);
        }
            
        foreach ($products->items() as $key => $product) {
            $subRecordId = 0;
            $products->items()[$key]->stock = productStockVerify($product, $product->prod_min_order_qty, $product->pov_code);
            $subRecordId = productColorId($product);
            $code = $product->pov_code ?? 0;
            $images = getProductImages($product->prod_id, $subRecordId);
            $products->items()[$key]->prod_image = productDummyImage();
            if (!empty($productImage = $images->first())) {
                $products->items()[$key]->prod_image = url('yokart/app/product/image/' . $product->prod_id . '/' . $product->pov_id . '?t=' . strtotime($productImage->afile_updated_at));
            }
        }

        $products = $products->toArray();
        
        $response['total'] = $products['total'];
        $response['pages'] = ceil($products['total'] / $products['per_page']);
        $response['products'] = $products['data'];
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    
    public function filters(Request $request)
    {
        $results = $request->all();
        $filters = $results;

        $productCategories = ProductCategory::getCategoriesForApp(config('constants.NO'));
        
        $productCategoriesCount = count($productCategories);
        if (!empty($productCategories) &&  $productCategoriesCount > 0) {
            $productCategories =  ProductCategory::buildTreeForApp($productCategories);
        }
        $results['categories'] =  $productCategories;
       

        $all_conditions = Product::PRODUCT_CONDITIONS;
        $new_all_conditions = [];
        foreach ($all_conditions as $key => $value) {
            $temp = [];
            $temp['value'] = $key;
            $temp['label'] = $value;
            $new_all_conditions[] = $temp;
        }
        $results['all_conditions'] = $new_all_conditions;

        $condition = false;
     
        $fields = 'prod_condition, brand_id as value, brand_name as label, ' . Product::getPrice();
        $products = Product::getAllProducts($filters, $fields, $condition);
        
        $results['price'] = [
            'min' => round(displayPrice($products->min('finalprice'), false, false)),
            'max' => round(displayPrice($products->max('finalprice'), false, false))
        ];
        $brands = Product::getBrandFiltersForApp($filters, false, $condition, $request["search_items"])->toArray();

        $results['brands'] = $brands;
        $results['options'] = Product::getOptionsFiltersForApp($filters, $condition, $request["search_items"]);

        return apiresponse(config('constants.SUCCESS'), '', $results);
    }

    public function detail(Request $request, $prod_id, $pov_code = '')
    {
        
        if (is_numeric($prod_id) == false) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_INVALID_REQUEST"));
        }
        
        $fields = 'prod_id, prod_name, prod_price, prod_type,pc_weight_unit,pc_weight, pov_default, coalesce(`pov_quantity`, `prod_stock`) as totalstock, prod_stock_out_sellable, prod_min_order_qty, cat_name,cat_id, brand_id, brand_name, pov_code,prod_self_pickup, prod_cod_available, prod_max_order_qty,' . Product::getPrice();
      
        $product = Product::productById($prod_id, $fields . ', pov_id,prod_description,prod_updated_on, pc_video_link,pc_warranty_age,pc_file_title, pc_return_age,pc_cancellation_age,  prod_model,pc_isbn, pc_hsn, pc_sac, pc_upc, coalesce(`pov_sku`, `pc_sku`) as sku, ' . Product::getPrice(), [], $pov_code);
        
        if (empty($product)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_PRODUCT_NOT_FOUND"));
        }
        
        // $recentViewProducts = $this->getRecentViewProducts($prod_id);
        $user_id = Auth::guard('api')->check() ? Auth::guard('api')->user()->user_id : 0;
        $token = request()->header('sess-token');
        $recentViewProducts = RecentlyViewedProduct::getRecentlyViewedProducts($prod_id, $token, $user_id);
        
        $record['recently_viewed_products'] = $recentViewProducts;
        $psrecords = ProductSpecification::where('ps_product_id', $prod_id)->get();
        $record['specifications'] = ProductSpecification::specificationHierarchyForApp($psrecords);
        $buytogetherProducts = [];
        $related_products = [];
        if (getConfigValueByName('APP_DISPLAY_BUY_TOGETHER')) {
            $buytogetherProducts = Product::getBuyTogetherProducts($fields . ',' . DB::Raw("CONCAT('" . url('') . "', '/yokart/app/product/image/', prod_id, '/', IF(pov_code IS NULL, '0', pov_id), '?t=', UNIX_TIMESTAMP(prod_updated_on)) as prod_image"), $prod_id, true, true);
        }
        if (getConfigValueByName('APP_DISPLAY_SIMILAR_PRODUCTS')) {
            $related_products = Product::getRelatedproducts($fields . ',ufp_id as favId,' . DB::Raw("CONCAT('" . url('') . "', '/yokart/app/product/image/', prod_id, '/', IF(pov_code IS NULL, '0', pov_id), '?t=', UNIX_TIMESTAMP(prod_updated_on)) as prod_image"), $prod_id);
        }
        $record['buytogether_products'] = $buytogetherProducts;
        $record['related_products'] = $related_products;
        $record['prod_id'] = $prod_id;

        $product->pc_video_link = !empty($product->pc_video_link) ? explode("\n", $product->pc_video_link) : [];

        $record['product'] = $product;

        $details = $this->detailsRecords($product);
        $subRecordId = productColorId($product);
   
        $record['product_images'] = getProductImages($prod_id, $subRecordId, true, getProdSize('medium'), 'app');
        $record['all_varients'] = $details['allVarients'];
        $record['rating'] = $details['rating'];
        $record['rating_count'] = $details['rating_count'];
        $record['size_chart'] = $details['sizeExist'] != false ? getFileUrl('productChartUpload', $prod_id) : null;
        $record['including_tax'] = (string) getConfigValueByName('PRICE_INCLUDING_TAX');

        $request["prod_id"] = $prod_id;
        $request["user_id"] = $user_id;
        $reviews = ProductReview::getReviewByProductIdForApp($request, 1, 2)->toArray();
        foreach ($reviews as $key => $review) {
            $reviews[$key]['preview_created_at'] = getConvertedDateTime($review['preview_created_at']);
        }
        $record['reviews'] = $reviews;

        $record['order_id'] = "";
        $record['can_post_review'] = "0";
        if (!empty($user_id)) {
            $can_post = ProductReview::authorizedToPost($user_id, $prod_id);
            if ($can_post["status"] === true && $can_post["redirect"] === false && !empty($can_post["data"]["order_id"])) {
                $record['can_post_review'] = "1";
                $record['order_id'] = (string) $can_post["data"]["order_id"];
            } elseif ($can_post["status"] === true && $can_post["redirect"] === true) {
                $record['can_post_review'] = "2";
            }
        }
        $policyArr = [];

        if (getConfigValueByName('PRODUCT_DETAIL_DISPLAY_WARRANTY') != 0) {
            $policyArr[] = [
                'key' => 'warranty',
                'value' => (string) $product->pc_warranty_age,
                'image' => url("") . "/yokart/media/policy.png"
            ];
        }
        if (getConfigValueByName('PRODUCT_DETAIL_DISPLAY_RETURN') != 0) {
            $policyArr[] = [
                'key' => 'return',
                'value' => (string) $product->pc_return_age,
                'image' => url("") . "/yokart/media/policy.png"
            ];
        }
        if ($product->prod_cod_available == 1 && getConfigValueByName('COD_ENABLE') == 1) {
            $policyArr[] = [
                'key' => 'cod',
                'value' => "",
                'image' => url("") . "/yokart/media/policy.png"
            ];
        }
        if ($product->prod_self_pickup != 1) {
            $policyArr[] = [
                'key' => 'pickup',
                'value' => "",
                'image' => url("") . "/yokart/media/policy.png"
            ];
        }
        if ($product->prod_self_pickup != 2) {
            $policyArr[] = [
                'key' => 'shipping',
                'value' => "",
                'image' => url("") . "/yokart/media/policy.png"
            ];
        }

        $record["policy"] = $policyArr;
        $record["url"] = !empty($product->urlRewrite) ? url('') . '/' . $product->urlRewrite->urlrewrite_custom : url('') . '/product/' . $prod_id;

        return apiresponse(config('constants.SUCCESS'), '', $record);
    }
    
    public function askQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prod_id' => 'required',
            // 'pov_code' => 'required',
            'subject' => 'required|max:255',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $records = $request->except(['prod_id', 'pov_code']);
        
        $records['thread_product_id'] = $request->input('prod_id');
        $records['thread_product_variant'] = $request->input('pov_code');
        $product = Product::select('prod_name')->where('prod_id', $request->input('prod_id'))->first();
        $records['thread_product_name'] = $product->prod_name;
        
        if (Thread::createMessageThread($records, Auth::guard('api')->user()->user_id)) {
            return apiresponse(config('constants.SUCCESS'), appLang('LBL_QUESTION_SUBMITTED'));
        }
        return apiresponse(config('constants.ERROR'), appLang("NOTI_SOMETHING_WENT_WRONG"));
    }

    public function reviews(Request $request, $prod_id, $page)
    {
        $request["prod_id"] = $prod_id;
        $request["user_id"] = Auth::guard('api')->check() ? Auth::guard('api')->user()->user_id : 0;
        $response = [];
        $reviews = ProductReview::getReviewByProductIdForApp($request, $page)->toArray();
        foreach ($reviews['data'] as $key => $review) {
            $reviews['data'][$key]['preview_created_at'] = getConvertedDateTime($review['preview_created_at']);
        }
        $response['total'] = $reviews['total'];
        $response['pages'] = ceil($reviews['total'] / $reviews['per_page']);
        $response['reviews'] = $reviews['data'];
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
    
    public function markAsHelpfulOrNot(Request $request, $preview_id)
    {
        $preview = ProductReview::where('preview_id', $preview_id)
        ->where('preview_publish', config('constants.YES'))
        ->where('preview_status', ProductReview::APPROVED)->first();
        if (empty($preview)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_INVALID_REQUEST"));
        }
        $helpfulOrNot = 1;
        $message = appLang("NOTI_REVIEW_MARKED_HELPFUL");
        if (!empty($request['type']) && $request['type'] === "nothelpful") {
            $helpfulOrNot = 0;
            $message = appLang("NOTI_REVIEW_MARKED_NOTHELPFUL");
        }
        ProductReviewHelpful::saveHelpful($preview_id, Auth::guard('api')->user()->user_id, $helpfulOrNot);

        $review = ProductReview::getReviewByIdForApp($preview_id, Auth::guard('api')->user()->user_id);
        $review->preview_created_at = getConvertedDateTime($review->preview_created_at);
        
        return apiresponse(config('constants.SUCCESS'), $message, ['review' => $review]);
    }

    private function detailsRecords($product)
    {
        $record['rating'] = ProductReview::getAverageRatingByProductId($product->prod_id);
        $record['rating_count'] = ProductReview::getReviewCount($product->prod_id);
        $record['allVarients'] = [];
        if (!empty($product->prod_id) && $product->pov_code != "") {
            $record['allVarients'] = ProductOptionVarient::getActiveRecordsByProductId($product->prod_id);
        }
        $record['sizeExist'] = getFileUrl('productChartUpload', $product->prod_id) ? true : false;
        return $record;
    }
}
