<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use Illuminate\Http\Request;
use App\Models\ProductCategory;

class CategoryController extends YokartController
{
    public function list(Request $request)
    {
        $response = [];
        $productCategories = ProductCategory::getCategoriesForApp();
        
        $productCategoriesCount = count($productCategories);
        if (!empty($productCategories) &&  $productCategoriesCount > 0) {
            $productCategories =  ProductCategory::buildTreeForApp($productCategories);
        }

        $response['categories'] = $productCategories;
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }
}
