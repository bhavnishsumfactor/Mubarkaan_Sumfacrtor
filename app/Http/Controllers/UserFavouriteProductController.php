<?php

namespace App\Http\Controllers;

use App\Models\UserFavouriteProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;
use Auth;

class UserFavouriteProductController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function updateFavourite(Request $request)
    {
        if (!$this->signed_in) {
            return jsonresponse(false, '', loginPopup());
        }
        $prodId  = $request->input('id');
        $userId  = $this->user->user_id;
        $records = ['ufp_prod_id' => $prodId, 'ufp_user_id' => $userId];
        if (!empty($request['code']) && $request->input('code') != "null") {
            $records['ufp_pov_code'] = $request->input('code');
        }
        $record = UserFavouriteProduct::where($records);
        $product = "";
        if ($record->count() != 0) {
            $record->delete();
        } else {
            UserFavouriteProduct::create($records);
            $fields =  'prod_id, prod_name, prod_price, pov_default, prod_stock_out_sellable, prod_min_order_qty, pov_code, prod_max_order_qty, ' . Product::getPrice();
            $product = Product::productById($prodId, $fields);
        }

        return jsonresponse(true, '', ['product' => $product, 'currency' => currencyCode()]);
    }
}
