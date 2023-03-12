<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminYokartController;
use App\Models\RelatedProduct;
use App\Models\Admin\AdminRole;
use App\Models\Product;
use DB;

class RelatedProductController extends AdminYokartController
{
    private $readPermission;
    private $writePermission;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
        $this->middleware(function ($request, $next) {
            if (!canRead(AdminRole::RELATED_PRODUCTS)) {
                return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
            }
            return $next($request);
        });
    }

    public function getListing(Request $request)
    {
        $data = [];

        $data['related'] = RelatedProduct::getProducts($request->all());

        $data['showEmpty'] = 0;
        if (empty($request['search']) && $data['related']->count() == 0) {
            $data['showEmpty'] = 1;
        }
        
        return jsonresponse(true, null, $data);
    }

    public function getProducts(Request $request)
    {
        return jsonresponse(true, null, Product::getProductsBySearch($request->input('query'), 10, !empty($request->input('prod_id'))?[$request->input('prod_id')]:[]));
    }

    public function getRelatedProducts(Request $request)
    {
        return jsonresponse(true, null, RelatedProduct::getLinkedProducts($request->input('prod_id')));
    }

    public function update(Request $request)
    {
        if (!canWrite(AdminRole::RELATED_PRODUCTS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        DB::beginTransaction();
        $productId = $request->input('prod_id');
        $recommended = $request->input('recommended');
        try {
            RelatedProduct::where('related_product_id', $productId)->delete();
            if (!empty($recommended)) {
                $recommended = json_decode($recommended);
                foreach ($recommended as $k => $v) {
                    RelatedProduct::create([
                      'related_product_id' => $productId,
                      'related_recommend_product_id' => $v->id
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return jsonresponse(true, __('NOTI_RELATED_UPDATED'));
    }
    
    public function delete(Request $request)
    {
        if (!canWrite(AdminRole::RELATED_PRODUCTS)) {
            return jsonresponse(false, __('NOTI_UNAUTHORIZED_ACCESS'), []);
        }
        if (empty($request->input('recommended_id'))) {
            RelatedProduct::where('related_product_id', $request->input('prod_id'))
            ->delete();
            return jsonresponse(true, __('NOTI_RELATED_DELETED'));
        }
        RelatedProduct::where('related_product_id', $request->input('prod_id'))
        ->where('related_recommend_product_id', $request->input('recommended_id'))
        ->delete();
        return jsonresponse(true, __('NOTI_PRODUCT_REMOVED_RELATED'));
    }
}
