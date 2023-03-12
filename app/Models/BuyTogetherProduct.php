<?php

namespace App\Models;

use App\Models\YokartModel;

class BuyTogetherProduct extends YokartModel
{
    public $timestamps = false;
    protected $fillable = ['btp_product_id', 'btp_recommend_product_id'];

    public static function getProducts($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = BuyTogetherProduct::select('btp_product_id as prod_id', 'products.prod_name');
        $query->join('products', 'products.prod_id', 'btp_product_id');
        if (!empty($request['search'])) {
            $query->where('products.prod_name', 'like', '%'.$request['search'].'%');
        }
        if($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1){
            $data = $query->groupBy('btp_product_id')->orderBy('btp_created_at','desc')->paginate((int)$per_page);
        }
        else{
            $data = $query->groupBy('btp_product_id')->orderBy('btp_created_at','desc')->paginate((int)$per_page, ['*'], 'page',  (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
        //$data = $query->groupBy('btp_product_id')->paginate((int)$per_page);
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k]->recommended = BuyTogetherProduct::getLinkedProducts($v->prod_id);
            }
        }
        return $data;
    }

    public static function getLinkedProducts($productId)
    {
        return BuyTogetherProduct::select('btp_recommend_product_id as prod_id', 'products.prod_name', 'products.prod_price')
        ->join('products', 'products.prod_id', 'btp_recommend_product_id')
        ->where('btp_product_id', $productId)
        ->get();
    }
}
