<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\YokartModel;
use Carbon\Carbon;

class RecentlyViewedProduct extends YokartModel
{
    use HasFactory;
    public $timestamps = false;
    public $fillable = ['rvp_user_id', 'rvp_token', 'rvp_prod_id', 'rvp_id'];

    public static function getProductsByToken($token)
    {
        return RecentlyViewedProduct::where('rvp_token', $token)->oldest('rvp_id')->get();
    }

    public static function getProductsByUser($userId)
    {
        return RecentlyViewedProduct::where('rvp_user_id', $userId)->oldest('rvp_id')->get();
    }

    public static function saveData($prodIds, $userId = 0, $token = "")
    {
        foreach ($prodIds as $prodId) {
            RecentlyViewedProduct::create([
                'rvp_user_id' => $userId,
                'rvp_token' => $token,
                'rvp_prod_id' => $prodId
            ]);
        }
    }

    public static function getRecentlyViewedProducts($prodId, $token, $userId)
    {
        $productids = [];
        $prodArray = [];
        $newArray = [];
        if (!empty($userId)) {
            $productids = RecentlyViewedProduct::getProductsByUser($userId)->pluck('rvp_prod_id')->toArray();
            if (!empty($prodId)) {
                RecentlyViewedProduct::where('rvp_user_id', $userId)->delete();
                $token = "";
            }
        } else {
            $productids = RecentlyViewedProduct::getProductsByToken($token)->pluck('rvp_prod_id')->toArray();
            if (!empty($prodId)) {
                RecentlyViewedProduct::where('rvp_token', $token)->delete();
            }
        }
        if (!empty($prodId)) {
            if (!empty($productids) && ($key = array_search($prodId, $productids)) !== false) {
                unset($productids[$key]);
            }
            if (!empty($productids)) {
                if (count($productids) > 9) {
                    $productids = array_slice($productids, 0, 9);
                }
                $prodArray = array_reverse($productids);
            }
            if ($prodArray) {
                array_push($prodArray, $prodId);
                $newArray = $prodArray;
            } else {
                $newArray =  [$prodId];
            }
            if (empty($token)) {
                $token = "";
            }
            RecentlyViewedProduct::saveData($newArray, $userId, $token);
        }
        $recentViewProducts = [];
        if (!empty($productids)) {
            $recentViewProducts = Product::getDataByIdsForApp($productids);
        }
        return $recentViewProducts;
    }

    public static function updateListing($userId, $token = "")
    {
        $productids = RecentlyViewedProduct::getProductsByToken($token)->pluck('rvp_prod_id')->toArray();
        RecentlyViewedProduct::where('rvp_token', $token)->delete();
        RecentlyViewedProduct::saveData($productids, $userId, "");
    }
}
