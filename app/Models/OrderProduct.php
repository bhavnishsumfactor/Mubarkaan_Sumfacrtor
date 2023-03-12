<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Order;
use App\Models\OrderReturnRequest;
use App\Models\AttachedFile;
use DB;

class OrderProduct extends YokartModel
{
    protected $primaryKey = 'op_id';
    public $timestamps = false;
    protected $fillable = [
        'op_order_id', 'op_qty', 'op_product_id', 'op_product_name', 'op_product_price', 'op_product_variants', 'op_prod_cost', 'op_product_sku', 'op_product_width', 'op_product_height', 'op_product_type', 'op_pov_code', 'op_product_weight', 'op_is_pickup', 'op_expired_on', 'op_return_age', 'pov_id'
    ];

    public static function userOrderedProduct($userId, $prodId)
    {
        return OrderProduct::select('op_id', 'order_id', 'order_shipping_status', 'op_pov_code', 'op_product_id')->where('op_product_id', $prodId)->where('order_user_id', $userId)->join('orders', 'orders.order_id', 'op_order_id')->latest('order_id')->get();
    }
  
    public function tax()
    {
        return $this->belongsTo('App\Models\OrderProductCharge', 'op_id', 'opc_op_id')->where('opc_type', 'tax');
    }
    public function refundPoints()
    {
        return $this->belongsTo('App\Models\OrderReturnRequest', 'op_id', 'orrequest_op_id')->join('user_reward_points', 'urp_orrequest_id', 'orrequest_id');
    }
    public function rewards()
    {
        return $this->belongsTo('App\Models\OrderProductCharge', 'op_id', 'opc_op_id')->where('opc_type', 'rewardpoints');
    }
    public function additionInfo()
    {
        return $this->belongsTo('App\Models\OrderProductAdditionInfo', 'op_id', 'opainfo_op_id');
    }
    public function cancelRequest()
    {
        return $this->belongsTo('App\Models\OrderReturnRequest', 'op_id', 'orrequest_op_id');
    }
    public function products()
    {
        return $this->hasMany('App\Models\Order', 'order_id', 'op_order_id');
    }
    public function urlRewrite()
    {
        return $this->belongsTo('App\Models\UrlRewrite', 'op_product_id', 'urlrewrite_record_id')->where('urlrewrite_type', UrlRewrite::PRODUCT_TYPE);
    }
    public function order()
    {
        return $this->hasOne('App\Models\Order', 'order_id', 'op_order_id');
    }
    public function orderSelective()
    {
        return $this->hasOne('App\Models\Order', 'order_id', 'op_order_id')->select('order_id');
    }
    public function productReview()
    {
        return $this->hasOne('App\Models\ProductReview', 'preview_prod_id', 'op_product_id')->join('orders', 'preview_order_id', 'order_id');
    }
    public function digitalData()
    {
        return $this->hasMany('App\Models\DigitalFileRecord', 'dfr_record_id', 'op_order_id')->leftJoin('attached_files', 'dfr_afile_id', 'afile_id');
    }
    public function bankInformation()
    {
        return $this->hasOne('App\Models\OrderReturnBankInfo', 'orbinfo_order_id', 'op_order_id');
    }
    public static function digitalOrderByUserId($userId, $condistions = [], $opId = '', $sortBy = '', $row = 0)
    {
        $query = OrderProduct::with('digitalData:dfr_name,dfr_record_id,dfr_subrecord_id,dfr_url,dfr_file_type,afile_physical_path')
            ->select('order_id', 'order_status', 'op_id', 'op_product_id', 'op_product_name', 'op_order_id', 'opainfo_max_download_limit as maxdownloadlimit', 'opainfo_downloads as totaldownload', 'order_date_added', 'op_expired_on')
            ->join('orders', 'order_products.op_order_id', 'orders.order_id')
            ->join('order_product_addition_info as pinfo', 'pinfo.opainfo_op_id', 'op_id')
            ->where('orders.order_user_id', $userId)
            ->where('orders.order_shipping_status', Order::SHIPPING_STATUS_DELIVERED)
            ->where('orders.order_payment_status', Order::PAYMENT_PAID)
            ->where('op_product_type', Product::DIGITAL_PRODUCT);
        if (count($condistions) > 0) {
            return $query->where($condistions)->first();
        }
        if ($opId != "") {
            $query->orderByRaw("IF(FIELD(op_id, $opId)=0,1,0),FIELD(op_id, $opId)");
        }
        if (isset($sortBy) && !empty($sortBy)) {
            if ($sortBy == 'dateDesc') {
                $query->orderBy('op_id', 'ASC');
            } elseif ($sortBy == 'dateAsc') {
                $query->orderBy('op_id', 'DESC');
            } elseif ($sortBy == 'alphabet') {
                $query->orderBy('op_product_name', 'asc');
            }
        } else {
            $query->latest('op_id');
        }

        if ($row != 0) {
            return $query->skip($row)->take(config('app.pagination'))->get();
        } else {
            return $query->paginate(config('app.pagination'));
        }
    }
    public function rewardPoints()
    {
        return $this->hasOne('App\Models\OrderProductCharge', 'opc_op_id', 'op_id')->where('opc_type', 'rewardpoints');
    }
    public static function getReturnSummary($requestIds, $orderId)
    {  
        return   OrderProduct::join('order_return_requests as request', 'order_products.op_id', 'request.orrequest_op_id')
            ->join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'request.orrequest_id')
            ->join('orders', 'orders.order_id', 'order_products.op_order_id')
            ->join('users', 'users.user_id', 'orders.order_user_id')
            ->select('op_product_name', 'op_pov_code', 'user_email', 'order_id', 'orrequest_type', 'order_shipping_value', 'order_currency_symbol', 'op_id', 'op_product_price', 'op_product_id', 'orrequest_qty', 'orrequest_status', 'orrequest_date', 'oramount_tax', 'oramount_shipping', 'oramount_discount', 'op_product_variants', 'orrequest_qty', 'oramount_giftwrap_price', 'oramount_reward_price', 'oramount_payment_status', 'orrequest_reason', 'orrequest_comment')
            ->with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')
            ->with('images:afile_record_id,afile_updated_at')
            ->whereIn('request.orrequest_id', $requestIds)->where('order_products.op_order_id', $orderId)->get();
    }
    public static function getReturnRecordsByUserId($userId, $type, $orderid = '', $row = 0)
    {
        $query = OrderProduct::with('tax')->join('order_return_requests as request', 'order_products.op_id', 'request.orrequest_op_id')
            ->join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'request.orrequest_id')
            ->join('orders', 'orders.order_id', 'order_products.op_order_id')
            ->join('users', 'users.user_id', 'orders.order_user_id')
            ->leftJoin('transactions', 'request.orrequest_id', 'transactions.txn_orrequest_id')
            ->select('op_product_name', 'orrequest_id', 'order_status', 'order_currency_symbol as currency_symbol', 'orrequest_order_status', 'op_product_variants', 'op_pov_code', 'user_email', 'order_id', 'orrequest_type', 'order_shipping_value', 'order_currency_symbol', 'op_id', 'op_product_price', 'op_product_id', 'orrequest_qty', 'orrequest_status', 'orrequest_date', 'oramount_tax', 'oramount_shipping', 'oramount_discount', 'orrequest_qty', 'oramount_giftwrap_price', 'oramount_reward_price', 'oramount_payment_status', 'order_shipping_type', 'orrequest_reason', 'orrequest_comment', 'order_discount_coupon_code', 'op_order_id', 'txn_gateway_transaction_id')
            ->with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')
            ->withCount('products')
            ->with('images:afile_record_id,afile_updated_at')
            ->with('bankInformation');
        if ($type == 'return') {
            $query->where('orrequest_type', OrderReturnRequest::RETURN);
        } elseif ($type == 'cancellation') {
            $query->where('orrequest_type', OrderReturnRequest::CANCELLATION);
        }
        if ($orderid) {
            $query->where('order_id', $orderid);
        }
        $query->where('order_user_id', $userId)->latest('orrequest_id');
        if ($row != 0) {
            return $query->skip($row)->take(config('app.pagination'))->get();
        } else {
            return $query->paginate(config('app.pagination'));
        }
    }
    public static function userProducts($request, $userId, $row = 0)
    {
        $query = OrderProduct::select('op_id', 'op_order_id', 'op_product_id', 'op_product_name', 'op_product_price', 'op_pov_code', 'op_product_variants', 'order_date_added', 'order_shipping_status')
            ->join('orders', 'orders.order_id', 'op_order_id')->where('order_user_id', $userId);
        $query->with(['productReview' => function ($relation) use ($userId) {
            $relation->leftJoin('product_review_logs', function ($sql) {
                $sql->on('prl_preview_id', 'preview_id');
            })
                ->select('prl_preview_title', 'prl_preview_description', 'prl_preview_rating', 'preview_id', 'prl_preview_id', 'preview_prod_id', 'preview_rating', 'preview_title', 'preview_description', 'preview_status')
                ->where('preview_user_id', $userId);
        }])
            ->withCount(['productReview' => function ($relation) use ($userId) {
                $relation->leftJoin('product_review_logs', function ($sql) {
                    $sql->on('prl_preview_id', 'preview_id');
                })->where('preview_user_id', $userId);
            }])
            ->with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')
            ->with('orderSelective.products:op_order_id,op_product_id')
            ->with('cancelRequest')
            ->with('images:afile_record_id,afile_updated_at');

        if (isset($request['orderProductId'])) {
            return $query->where('op_id', $request['orderProductId'])->first();
        }
        $filterBy = !empty($request['filters']) ? $request['filters'] : '';
        switch ($filterBy) {
            case 'showReviewed':
                $query->having('product_review_count', '>', 0);
                break;
            case 'showPending':
                $query->having('product_review_count', 0);
                break;
            default:
                break;
        }
        $query->latest('op_id');
        if ($row != 0) {
            return $query->skip($row)->take(5)->get()->toArray();
        } else {
            return $query->paginate(7);
        }
    }
    public static function orderProductById($opId)
    {
        return OrderProduct::select('op_product_id', 'op_order_id', 'op_product_name', 'op_pov_code', 'op_product_variants')
            ->where('op_id', $opId)
            ->first();
    }
    public static function getByOrderIdProductId($orderId, $prodId)
    {
        return OrderProduct::select('op_product_id', 'op_product_name', 'op_pov_code', 'op_product_price', 'op_qty', 'op_product_variants', 'op_id')
            ->where('op_product_id', $prodId)
            ->where('op_order_id', $orderId)
            ->first();
    }
    public static function getByOrderId($orderId)
    {
        return OrderProduct::select('op_product_id', 'op_product_name', 'op_product_type', 'op_pov_code', 'op_product_variants', 'op_id')
            ->where('op_order_id', $orderId)
            ->get();
    }

    public static function getRequestsByUserId($userId, $type, $sum = false)
    {
        $query = OrderProduct::join('order_return_requests as request', 'order_products.op_id', 'request.orrequest_op_id')
            ->join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'request.orrequest_id')
            ->join('orders', 'orders.order_id', 'order_products.op_order_id')
            ->join('users', 'users.user_id', 'orders.order_user_id')
            ->select('orrequest_id', 'orrequest_date as order_date_added', 'order_id');
        if ($type == 'return') {
            $query->where('orrequest_type', 1);
        } elseif ($type == 'cancel') {
            $query->where('orrequest_type', 2);
        }
        $query->where('order_user_id', $userId);

        $query->addSelect(DB::raw("SUM(op_product_price * orrequest_qty + oramount_tax + oramount_shipping - oramount_discount - oramount_reward_price) as order_net_amount"));
        if ($sum == true) {
            return  $query->first()->order_net_amount;
        }
        return $query->groupBy('orrequest_id')->latest('orrequest_id')->paginate(config('app.pagination'));
    }

    public static function getRequestsByOrderId($orderId)
    {
        $query = OrderProduct::join('order_return_requests as request', 'order_products.op_id', 'request.orrequest_op_id')
            ->join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'request.orrequest_id')
            ->join('orders', 'orders.order_id', 'order_products.op_order_id')
            ->join('users', 'users.user_id', 'orders.order_user_id')
            ->select('orrequest_id', 'orrequest_date as order_date_added', 'order_id', DB::raw('DATE_FORMAT(orrequest_date, "%Y-%m-%d") AS orrequest_date'), 'oramount_tax', 'oramount_shipping');
        $query->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED);
        $query->where('orrequest_order_id', $orderId);
        $query->addSelect(DB::raw("SUM(op_product_price * orrequest_qty - oramount_discount) as order_net_amount"));
        return  $query->first();
    }
    public static function getDigitalAdditionalInfo($orderId)
    {
        return OrderProduct::join('order_product_addition_info as ainfo', 'order_products.op_id', 'ainfo.opainfo_op_id')
            ->select('op_product_name', 'op_id', 'op_qty', 'op_product_id')->where(['op_order_id' => $orderId, 'opainfo_upload_additional_files' => 1])->get();
    }

    public function images()
    {
        return $this->hasOne('App\Models\AttachedFile', 'afile_record_id', 'op_product_id')->where('attached_files.afile_type', '=', AttachedFile::SECTIONS['productImages'])->orderBy('attached_files.afile_updated_at', 'DESC');
    }
    public static function userProductsForApp($request)
    {
        $userId = $request['userId'];

        $query = OrderProduct::select(
            'op_id',
            'op_qty',
            'op_order_id',
            'op_product_id',
            'op_product_name',
            'op_product_price',
            'op_pov_code', 
            'op_product_variants',
            'order_date_added', 
            'order_shipping_status',
            DB::Raw("CONCAT('" . url('') . "', '/yokart/app/product/image/', op_product_id, '/', IF(pov_id IS NULL, '0', pov_id),  '?t=', UNIX_TIMESTAMP(order_date_added)) as prod_image"),
            DB::Raw("IF(op_pov_code IS NULL, '', op_pov_code) as pov_code")
        )->join('orders', 'orders.order_id', 'op_order_id')->where('order_user_id', $userId)
        ->leftjoin('product_option_varients as pov', 'pov.pov_code', 'op_pov_code');
        $query->with(['productReview' => function ($relation) use ($userId) {
            $relation->leftJoin('product_review_logs', function ($sql) {
                $sql->on('prl_preview_id', 'preview_id');
            })
                ->select('prl_preview_title', 'prl_preview_description', 'prl_preview_rating', 'preview_id', 'prl_preview_id', 'preview_prod_id', 'preview_rating', 'preview_title', 'preview_description', 'preview_status')
                ->where('preview_user_id', $userId);
        }])
            ->withCount(['productReview' => function ($relation) use ($userId) {
                $relation->leftJoin('product_review_logs', function ($sql) {
                    $sql->on('prl_preview_id', 'preview_id');
                })->where('preview_user_id', $userId);
            }])
            ->with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')
            ->with('orderSelective.products:op_order_id,op_product_id')
            ->with('cancelRequest')
            ->with('images:afile_record_id,afile_updated_at');

        if (isset($request['orderProductId'])) {
            return $query->where('op_id', $request['orderProductId'])->first();
        }
        $filterBy = !empty($request['type']) ? $request['type'] : '';
        switch ($filterBy) {
            case 'submitted':
                $query->having('product_review_count', '>', 0);
                break;
            case 'pending':
                $query->having('product_review_count', 0);
                break;
            default:
                break;
        }
        $query->latest('op_id');
        return $query->paginate((int) config('app.app_pagination'), ['*'], 'page', $request['page']);
    }
    public static function getDigitalOrderForApp($request, $page, $opId = "")
    {
        $query = OrderProduct::select('op_id', 'op_product_id', 'op_product_name', 'op_order_id', 'opainfo_max_download_limit as maxdownloadlimit', 'opainfo_downloads as totaldownload', 'op_expired_on')
            ->with(array('digitalData' => function ($query) {
                $query->select('dfr_name', 'dfr_record_id', 'dfr_subrecord_id', 'dfr_url', 'dfr_file_type', 'afile_record_id', 'afile_physical_path', DB::raw("CONCAT('" . url('') . "', '/storage/', afile_physical_path) as file_path"));
            }))
            ->join('orders', 'order_products.op_order_id', 'orders.order_id')
            ->join('order_product_addition_info as pinfo', 'pinfo.opainfo_op_id', 'op_id')
            ->where('orders.order_user_id', $request['user_id'])
            ->where('orders.order_shipping_status', Order::SHIPPING_STATUS_DELIVERED)
            ->where('orders.order_payment_status', Order::PAYMENT_PAID)
            ->where('op_product_type', Product::DIGITAL_PRODUCT);
            
        if ($opId != "") {
            $query->orderByRaw("IF(FIELD(op_id, $opId)=0,1,0),FIELD(op_id, $opId)");
        }
        $sortBy = !empty($request['sort']) ? $request['sort'] : '';
        if (isset($sortBy) && !empty($sortBy)) {
            if ($sortBy == 'oldest') {
                $query->orderBy('op_id', 'ASC');
            } elseif ($sortBy == 'latest') {
                $query->orderBy('op_id', 'DESC');
            } elseif ($sortBy == 'alphabetical') {
                $query->orderBy('op_product_name', 'asc');
            }
        } else {
            $query->latest('op_id');
        }

        return $query->paginate((int) config('app.app_pagination'), ['*'], 'page', $page);
    }
    public static function getReturnRecordsForApp($userId, $type, $page, $orderid = '')
    {
        $query = OrderProduct::with('tax')->join('order_return_requests as request', 'order_products.op_id', 'request.orrequest_op_id')
            ->join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'request.orrequest_id')
            ->join('orders', 'orders.order_id', 'order_products.op_order_id')
            ->join('users', 'users.user_id', 'orders.order_user_id')
            ->leftJoin('transactions', 'request.orrequest_id', 'transactions.txn_orrequest_id')
            ->select('op_product_name', 'orrequest_id', 'order_status', 'order_currency_symbol as currency_symbol', 'orrequest_order_status', 'op_product_variants', 'op_pov_code', 'user_email', 'order_id', 'orrequest_type', 'order_shipping_value', 'order_currency_symbol', 'op_id', 'op_product_price', 'op_product_id', 'orrequest_qty', 'orrequest_status', 'orrequest_date', 'oramount_tax', 'oramount_shipping', 'oramount_discount', 'orrequest_qty', 'oramount_giftwrap_price', 'oramount_reward_price', 'oramount_payment_status', 'order_shipping_type', 'orrequest_reason', 'orrequest_comment', 'order_discount_coupon_code', 'order_reward_amount','op_order_id','order_net_amount', 'txn_gateway_transaction_id')
            // ->with('urlRewrite:urlrewrite_record_id,urlrewrite_original,urlrewrite_custom')
            ->withCount('products')
            // ->with('images:afile_record_id,afile_updated_at')
            ->with(array('images' => function ($query) {
                $query->select('afile_record_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id,'?t=', UNIX_TIMESTAMP(afile_updated_at)) as product_image"));
            }))
            ->withCasts([
                'op_product_variants' => 'object'
            ])
            ->with('bankInformation');
        if ($type == 'return') {
            $query->where('orrequest_type', OrderReturnRequest::RETURN);
        } elseif ($type == 'cancellation') {
            $query->where('orrequest_type', OrderReturnRequest::CANCELLATION);
        } else {
            $query->whereIn('orrequest_type', [OrderReturnRequest::RETURN, OrderReturnRequest::CANCELLATION]);
        }
        if ($orderid) {
            $query->where('order_id', $orderid);
        }
        $query->where('order_user_id', $userId)->latest('orrequest_id');
        return $query->paginate((int) config('app.app_pagination'), ['*'], 'page', $page);
    }
}
