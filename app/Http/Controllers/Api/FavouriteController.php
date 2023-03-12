<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\YokartController;
use App\Models\UserFavouriteProduct;
use App\Models\Product;
use App\Models\AppCollectionToRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class FavouriteController extends YokartController
{
    public function list(Request $request, $page)
    {
        $response = [];
        $filters = $request->input("filters");
        $favourites = Product::getFavouritesForApp(Auth::guard('api')->user()->user_id, $page, $filters);
        $favourites = $favourites->toArray();
        $response['favourites'] = $favourites['data'];
        $response['total'] = $favourites['total'];
        $response['pages'] = ceil($favourites['total'] / $favourites['per_page']);
        $response['categories'] = AppCollectionToRecord::getFavCategories();
        return apiresponse(config('constants.SUCCESS'), '', $response);
    }

    public function remove(Request $request, $ufpId)
    {
        $favRecord = UserFavouriteProduct::where('ufp_user_id', Auth::guard('api')->user()->user_id)
            ->where('ufp_id', $ufpId)->first();
        if (empty($favRecord)) {
            return apiresponse(config('constants.ERROR'), appLang("NOTI_INVALID_REQUEST"));
        }
        $favRecord->delete();
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_ITEM_REMOVED_FROM_FAVOURITES"));
    }

    public function addItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return apiresponse(config('constants.ERROR'), $validator->errors()->first());
        }
        $pov_code = $request->input("pov_code");
        $favRecord = UserFavouriteProduct::select('ufp_id')->where('ufp_user_id', Auth::guard('api')->user()->user_id)
            ->where('ufp_prod_id', $request->input("product_id"))
            ->where('ufp_pov_code', $pov_code)
            ->first();
        if (!empty($favRecord)) {
            return apiresponse(config('constants.SUCCESS'), appLang("NOTI_ITEM_ADDED_TO_FAVOURITES"), ['ufp_id' => $favRecord->ufp_id]);
        }
        $ufp_id = UserFavouriteProduct::create([
            'ufp_user_id' => Auth::guard('api')->user()->user_id,
            'ufp_prod_id' => $request->input("product_id"),
            'ufp_pov_code' => $pov_code,
        ])->ufp_id;
        return apiresponse(config('constants.SUCCESS'), appLang("NOTI_ITEM_ADDED_TO_FAVOURITES"), ['ufp_id' => $ufp_id]);
    }
}
