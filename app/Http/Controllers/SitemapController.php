<?php

namespace App\Http\Controllers;

use App\Http\Controllers\YokartController;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Page;
use App\Models\ProductCategory;

class SitemapController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $brandFields = ['brand_id', 'brand_name'];
        $brandConditions = ['brand_publish' => config('constants.YES')];
        $brands = Brand::getRecords($brandFields, $brandConditions);
        
        $pages  = Page::getAllPages($request, 0, [3,4])->toArray();
        $pages  = $pages['data'];
        $productCategories = ProductCategory::getCategories();
        if (!empty($productCategories) && count($productCategories) > 0) {
            $productCategories =  ProductCategory::buildTree($productCategories);
        }
        return view('themes.' . config('theme') . '.sitemap', compact('brands', 'pages', 'productCategories'));
    }
}
