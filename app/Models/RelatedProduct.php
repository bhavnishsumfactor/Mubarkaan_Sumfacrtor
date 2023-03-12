<?php

namespace App\Models;

use App\Models\YokartModel;

class RelatedProduct extends YokartModel
{
    public $timestamps = false;
    protected $fillable = ['related_product_id', 'related_recommend_product_id'];

    public static function getProducts($request)
    {
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = RelatedProduct::select('related_product_id as prod_id', 'products.prod_name');
        $query->join('products', 'products.prod_id', 'related_product_id');
        if (!empty($request['search'])) {
            $query->where('products.prod_name', 'like', '%'.$request['search'].'%');
        }
        //$data = $query->groupBy('related_product_id')->paginate((int)$per_page);
        if($query->paginate((int)$per_page)->count() >= 1 && $query->paginate((int)$per_page)->currentPage() >= 1){
            $data = $query->groupBy('related_product_id')->orderBy('related_created_at','desc')->paginate((int)$per_page);
        }
        else{
            $data = $query->groupBy('related_product_id')->orderBy('related_created_at','desc')->paginate((int)$per_page, ['*'], 'page',  (int)$query->paginate((int)$per_page)->currentPage()-1);
        }
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$k]->recommended = RelatedProduct::getLinkedProducts($v->prod_id);
            }
        }
        return $data;
    }

    public static function getLinkedProducts($productId)
    {
        return RelatedProduct::select('related_recommend_product_id as prod_id', 'products.prod_name', 'products.prod_price')
        ->join('products', 'products.prod_id', 'related_recommend_product_id')
        ->where('related_product_id', $productId)
        ->get();
    }
}
