<?php

namespace App\Models;

use App\Models\YokartModel;

class UserFavouriteProduct extends YokartModel
{
    public $timestamps = false;

    protected $primaryKey = 'ufp_id';
    protected $fillable = ['ufp_user_id', 'ufp_prod_id', 'ufp_pov_code','ufp_created_at'];

    public static function getRrecordsByUserId($userId)
    {
        $per_page = config('app.pagination');
        return  UserFavouriteProduct::join('products', 'products.prod_id', 'user_favourite_products.ufp_prod_id')
         ->select('prod_id', 'prod_name', 'prod_price')
         ->where('ufp_user_id', $userId)->paginate((int)$per_page);
    }
}
