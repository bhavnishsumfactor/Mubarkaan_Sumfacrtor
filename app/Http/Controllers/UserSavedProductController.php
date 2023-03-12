<?php

namespace App\Http\Controllers;

use App\Models\UserSavedProduct;
use App\Models\Product;
use App\Models\ProductStockHold;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use App\Http\Controllers\YokartController;
use Auth;
use Cart;

class UserSavedProductController extends YokartController
{ 
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth')->only('destory');
    } 
  
    public function destroy(Request $request)
    {
        UserSavedProduct::where('usp_id', $request->input('id'))->delete();
        return jsonresponse(true, __('NOTI_ITEM_REMOVED_SUCCESSFULLY'));
    }
    public function destroyAll(Request $request)
    {
        UserSavedProduct::where('usp_session_id', $this->cartSession)->where('usp_temp', 1)->delete();
        return jsonresponse(true, __('NOTI_ITEM_REMOVED_SUCCESSFULLY'));
    }
}
