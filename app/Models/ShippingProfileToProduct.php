<?php

namespace App\Models;

use App\Models\YokartModel;

class ShippingProfileToProduct extends YokartModel
{
    public $timestamps = false;
    protected $fillable = ['sprod_sprofile_id','sprod_prod_id'];

    public static function deleteRecords($profileId, $productIds)
    {
        ShippingProfileToProduct::where(function ($query) use ($profileId, $productIds) {
            $query->whereIn('sprod_prod_id', $productIds)
            ->orWhere('sprod_sprofile_id', $profileId);
        })->delete();
    }
    public static function getProductsById($profileId)
    {
      return ShippingProfileToProduct::join('products', 'products.prod_id', 'shipping_profile_to_products.sprod_prod_id')
        ->select('prod_id', 'prod_name')->where('sprod_sprofile_id', $profileId)->get();
    }
}
