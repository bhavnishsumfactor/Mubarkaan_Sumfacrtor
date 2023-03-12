<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Controllers\YokartController;
use Illuminate\Http\Request;

class CategoryController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function displayAllCategories()
    {
        $fields = ['cat_id', 'cat_name'];
        $conditions = ['cat_publish' => config('constants.YES'), 'cat_parent' => config('constants.NO')];
        $with = ['urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom'];
        $with = ['uploadImages:afile_id,afile_record_id,afile_updated_at'];
        $categories = ProductCategory::getRecords($fields, $conditions, $with);
        return view('categories', compact("categories"));
    }

    public function index($catId)
    {
        $category = ProductCategory::getById($catId);
        if (empty($category->cat_id)) {
            abort(404);
        }

        $filters = [
            'categories' => $category->cat_id
        ];
        $fields = 'prod_id, prod_name,  pov_default, brand_name, brand_id, pov_display_title,
        prod_stock_out_sellable, prod_min_order_qty,cat_id ,cat_name, pov_code, pov_quantity,
         prod_max_order_qty,' . Product::getPrice();
        $products = Product::getlisting($fields, $filters);
        $records['category'] = $category;
        $records['products'] = $products;
        $records['filterType'] = "categories";
        $records['filterValue'] =  $category->cat_id;
        $records['searchedBy'] =  "categories";
        $records['record_id'] =  $category->cat_id;
        return view('themes.' . config('theme') . '.product.listing', $records);
    }

    public function getAllCats($catId)
    {
        $cats = [];
        $this->getCategories($catId, $cats);
        return $cats;
    }

    public function getCategories($parentId, &$cats)
    {
        $parentCat = ProductCategory::where('cat_parent', $parentId)->where('cat_publish', 1)->get();

        $categories = [];

        foreach ($parentCat as $category) {
            // if ($category->cat_parent != 0) {
            $cats[$category->cat_id]['name'] =  $category->cat_name;
            $children = $this->getCategories($category->cat_id, $cats[$category->cat_id]['children']);
            if (!empty($children)) {
                $cats[$category->cat_id]['children'][] = $children;
            }
            // }
        }
    }
}
