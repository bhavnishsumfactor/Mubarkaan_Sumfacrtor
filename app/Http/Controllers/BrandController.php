<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;

class BrandController extends YokartController
{
    public function index($brandId)
    {
        $brand = Brand::getRecordsById($brandId);
        if (empty($brand->brand_id)) {
            abort(404);
        }
        $filters = [
            'brands' => $brandId
        ];
        $fields = 'prod_id, prod_name,  pov_default, brand_name, brand_id, pov_display_title,
        prod_stock_out_sellable, prod_min_order_qty,cat_id ,cat_name, pov_code, pov_quantity,
         prod_max_order_qty,' . Product::getPrice();
        $products = Product::getListing($fields, $filters);
        $records['products'] = $products;
        $records['brand'] = $brand;
        $records['filterType'] = "brands";
        $records['filterValue'] = $brandId;
        $records['searchedBy'] = "brands";
        $records['record_id'] = $brandId;

        
        return view('themes.' . config('theme') .'.product.listing', $records);
    }
}
