<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\orderInvoice;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DB;

class Order extends YokartModel
{
    public const CREATED_AT = 'order_date_added';
    public const UPDATED_AT = 'order_date_updated';

    public const PAYMENT_PENDING = 1;
    public const PAYMENT_PAID = 2;

    public const ORDER_FROM_WEB = 1;
    public const ORDER_FROM_APP = 2;

    public const ORDER_FROM = [
        'web' => self::ORDER_FROM_WEB,
        'app' => self::ORDER_FROM_APP
    ];

    public const PAYMENT_STATUS = [
        self::PAYMENT_PENDING => 'Pending',
        self::PAYMENT_PAID => 'Paid'
    ];

    public const ORDER_STATUS_OPEN = 1;
    public const ORDER_STATUS_PARTIAL_RETURNED = 2;
    public const ORDER_STATUS_RETURNED = 3;
    public const ORDER_STATUS_COMPLETED = 4;


    public const ORDER_PICKUP = 1;
    public const ORDER_SHIPPING = 0;


    public const SHIPPING_STATUS_IN_PROGRESS = 1;
    public const SHIPPING_STATUS_READY_FOR_SHIPPING = 2;
    public const SHIPPING_STATUS_SHIPPED = 3;
    public const SHIPPING_STATUS_DELIVERED = 4;


    public const ORDER_STATUS = [
        self::ORDER_STATUS_OPEN => 'open',
        self::ORDER_STATUS_COMPLETED => 'Completed',
        self::ORDER_STATUS_RETURNED => 'Return',
        self::ORDER_STATUS_PARTIAL_RETURNED => 'Partial Return'
    ];
    public const SHIPPING_STATUS = [
        self::SHIPPING_STATUS_IN_PROGRESS => 'in-progress',
        self::SHIPPING_STATUS_READY_FOR_SHIPPING => 'ready-for-shipping',
        self::SHIPPING_STATUS_SHIPPED => 'shipped',
        self::SHIPPING_STATUS_DELIVERED => 'delivered',
    ];
    public const DIGITAL_STATUS = [
        self::SHIPPING_STATUS[self::SHIPPING_STATUS_IN_PROGRESS] => 'reviewing',
        self::SHIPPING_STATUS[self::SHIPPING_STATUS_READY_FOR_SHIPPING] => 'processed',
        self::SHIPPING_STATUS[self::SHIPPING_STATUS_SHIPPED] => 'perparing-link',
        self::SHIPPING_STATUS[self::SHIPPING_STATUS_DELIVERED] => 'ready-for-download',
    ];
    public const PICKUP_STATUS = [
        self::SHIPPING_STATUS_IN_PROGRESS => 'reviewing',
        self::SHIPPING_STATUS_READY_FOR_SHIPPING => 'packing',
        self::SHIPPING_STATUS_SHIPPED => 'ready-for-pickup',
        self::SHIPPING_STATUS_DELIVERED => 'picked-up',
    ];

    public const STATUS_COLORS = [
        self::SHIPPING_STATUS_IN_PROGRESS => '#cad590',
        self::SHIPPING_STATUS_READY_FOR_SHIPPING => '#f5ba78',
        self::SHIPPING_STATUS_SHIPPED => '#55b3c2',
        self::SHIPPING_STATUS_DELIVERED => '#55c158',
    ];

    protected $fillable = [
        'order_user_id', 'order_payment_status', 'order_status', 'order_net_amount', 'order_net_amount',
        'order_amount_charged', 'order_tax_charged', 'order_payment_method', 'order_discount_coupon_code',
        'order_discount_type', 'order_discount_amount', 'order_currency_code', 'order_notes', 'order_shipping_type', 'order_currency_value', 'order_reward_points', 'order_reward_amount', 'order_shipping_value', 'order_shipping_methods', 'order_cart_data', 'order_shipping_status', 'order_currency_symbol', 'order_gift_amount', 'order_pending_rewards'
    ];

    public static function flipedStatus()
    {
        return array_flip(self::SHIPPING_STATUS);
    }

    public static function getRecords($request, $status = -1, $row = 0)
    {
        $query =  Order::join('users', 'users.user_id', 'orders.order_user_id')
            ->select(
                'order_id as id',
                'order_status',
                'order_shipping_status',
                'order_payment_status as payment_status',
                'order_id',
                'order_net_amount',
                'order_amount_charged',
                'order_shipping_type',
                'order_payment_method',
                'order_date_added',
                'order_gift_amount',
                'order_currency_symbol as currency_symbol',
                'order_currency_value',
                DB::raw("(
                CASE
                    WHEN order_payment_status = " . self::PAYMENT_PAID . " THEN '" . Order::PAYMENT_STATUS[self::PAYMENT_PAID] . "' 
                    ELSE '" . Order::PAYMENT_STATUS[self::PAYMENT_PENDING] . "' END
                ) as order_payment_status")
            )->withCount('products as total_products')
            ->withCount(['returnProducts as pending_requests' => function ($rSql) {
                $rSql->whereNotIn('orrequest_status', [OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED, OrderReturnRequest::RETURN_REQUEST_STATUS_DECLINED]);
            }])
            ->withCount(['products as digital_products' => function ($sql) {
                $sql->where('op_product_type', Product::DIGITAL_PRODUCT);
            }])


            ->when($status != -1, function ($q) use ($status, $request) {
                $statusText = Str::slug(Order::SHIPPING_STATUS[$status]);
                if ($request['active-tab'] == 'pickup') {
                    $statusText = Str::slug(Order::PICKUP_STATUS[$status]);
                }

                return $q->where('order_shipping_status', $status)->addSelect(DB::raw("('$statusText') as status"));
            })->where('order_status', '!=', self::ORDER_STATUS_COMPLETED);


        $shipType = self::ORDER_SHIPPING;
        if ($request['active-tab'] == 'pickup') {
            $shipType = self::ORDER_PICKUP;
        }
        $query->where('order_shipping_type', $shipType);

        static::orderSearch($query, $request['search'], false);
        if (empty($search['keywords']) && empty($search['sortby'])) {
            $query->latest('order_id');
        }
        if ($row != 0) {
            return $query->offset($row)->take(5)->get()->toArray();
        } else {
            return $query->paginate(5);
        }
    }
    public static function getReturnRecords($request, $type, $status = -1, $row = 0)
    {
        $statusText = Str::slug(OrderReturnRequest::REQUEST_STATUS[$status]);

        $query =  Order::with('products:op_order_id')->join('order_return_requests as req', 'req.orrequest_order_id', 'orders.order_id')
            ->join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'req.orrequest_id')
            ->join('users', 'users.user_id', 'orders.order_user_id')
            ->join('order_products', 'order_products.op_id', 'req.orrequest_op_id')
            ->leftJoin('order_product_charges as opc', function ($query) {
                $query->on('order_products.op_id', 'opc.opc_op_id')->where('opc_type', 'tax');
            })->with(['transaction' => function ($txnSql) {
                $txnSql->select('txn_gateway_type', 'txn_order_id')->where('txn_type', Transaction::DEBIT_TYPE);
            }])->select(
                'orrequest_id as id',
                'order_status',
                'order_id',
                'opc_value as tax',
                'op_product_price',
                'orrequest_type',
                'orrequest_qty',
                'order_amount_charged',
                'order_shipping_value',
                'order_net_amount',
                'order_payment_status as payment_status',
                'order_payment_method',
                'orrequest_date as order_date_added',
                'order_currency_symbol as currency_symbol',
                'order_currency_code',
                'order_currency_value',
                'oramount_tax',
                'oramount_shipping',
                'oramount_discount',
                'oramount_giftwrap_price',
                'oramount_reward_price',
                DB::raw("(
                CASE
                    WHEN order_payment_status = " . Order::PAYMENT_PAID . " THEN '" . Order::PAYMENT_STATUS[Order::PAYMENT_PAID] . "' 
                    ELSE '" . Order::PAYMENT_STATUS[Order::PAYMENT_PENDING] . "' END
                ) as order_payment_status")
            )
            ->when($status != -1, function ($q) use ($status, $statusText) {
                return $q->where('req.orrequest_status', $status)->addSelect(DB::raw("('$statusText') as status"));
            })->where('order_status', '!=', self::ORDER_STATUS_COMPLETED)->where('orrequest_type', $type);

        static::orderSearch($query, $request['search'], false, true);

        if (empty($search['keywords']) && empty($search['sortby'])) {
            $query->latest('order_id');
        }

        if ($row != 0) {
            return $query->offset($row)->take(5)->get()->toArray();
        } else {
            return $query->paginate(5);
        }
    }
    public static function getOrders($request)
    {
        $result = [];
        $per_page = config('app.pagination');
        if (!empty($request['per_page'])) {
            $per_page = $request['per_page'];
        }
        $query = Order::with(['transaction' => function ($txnSql) {
            $txnSql->select('txn_gateway_type', 'txn_order_id', 'txn_gateway_transaction_id')->where('txn_type', Transaction::DEBIT_TYPE);
        }])->leftjoin('users', 'users.user_id', 'orders.order_user_id')
            ->select(
                'order_id',
                'order_status',
                'order_net_amount',
                'user_id',
                'order_shipping_status',
                'order_shipping_methods',
                'order_payment_method',
                'order_gift_amount',
                'order_payment_status as payment_status',
                'order_date_added',
                'order_currency_symbol as currency_symbol',
                'order_shipping_type',
                'order_currency_value',
                DB::raw("(
                    CASE
                        WHEN order_payment_status = " . self::PAYMENT_PAID . " THEN '" . Order::PAYMENT_STATUS[self::PAYMENT_PAID] . "' 
                        ELSE '" . Order::PAYMENT_STATUS[self::PAYMENT_PENDING] . "' END
                    ) as order_payment_status"),
                DB::raw("CONCAT(user_fname, ' ', user_lname) as username")
            );

        if (!empty($request['pstatus']) && $request['pstatus'] == 'PENDING') {
            $query->where('order_payment_status', self::PAYMENT_PENDING);
        }
        if (empty($request['return_status'])) {
            if (!empty($request['order_status'])) {
                $query->where('order_status', $request['order_status']);
            } elseif ($request['active-tab'] != 'all') {
                $query->where('order_status', '!=', self::ORDER_STATUS_COMPLETED);
            }
        }
        $return = false;
        if (!empty($request['return_status'])) {
            $query->join('order_return_requests as req', 'req.orrequest_order_id', 'orders.order_id')
                ->join('order_products', 'order_products.op_id', 'req.orrequest_op_id')
                ->join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'req.orrequest_id');
            $query->addSelect('orrequest_id', 'op_product_price', 'orrequest_status', 'orrequest_type', 'orrequest_qty', 'oramount_tax', 'oramount_shipping', 'oramount_discount', 'oramount_giftwrap_price', 'oramount_reward_price', 'orrequest_qty')
                ->where('orrequest_type', $request['return_status'])->orderBy('req.orrequest_id', 'DESC');
            $return = true;
        }

        static::orderSearch($query, $request['search'], true, $return);

        if (!empty($request['user_id'])) {
            $query->where('order_user_id', $request['user_id']);
            $result['order_total'] = $query->sum('order_net_amount');
        }
        if (empty($search['keywords']) && empty($search['sortby']) && empty($request['return_status'])) {
            $query->latest('order_id');
        }
        $query->withCount('products as total_products')
            ->withCount('returnProducts')
          
            ->withCount(['products as digital_products' => function ($sql) {
                $sql->where('op_product_type', Product::DIGITAL_PRODUCT);
            }])->withCount(['returnProducts as pending_requests' => function ($rSql) {
                $rSql->whereNotIn('orrequest_status', [OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED, OrderReturnRequest::RETURN_REQUEST_STATUS_DECLINED]);
            }]);
        if ($request['active-tab'] == 'pickup') {
            $query->where('order_shipping_type', self::ORDER_PICKUP);
        } elseif ($request['active-tab'] == 'open') {
            $query->where('order_shipping_type', self::ORDER_SHIPPING);
        }
        /*} elseif ($request['active-tab'] == 'open') {
            $query->where('order_shipping_type', self::ORDER_SHIPPING);
        }*/



        if ($query->paginate((int) $per_page)->count() >= 1 && $query->paginate((int) $per_page)->currentPage() >= 1) {
            $result['orders'] = $query->paginate((int) $per_page);
        } else {
            $result['orders'] = $query->paginate((int) $per_page, ['*'], 'page', (int) $query->paginate((int) $per_page)->currentPage() - 1);
        }

        return $result;
    }
    public static function orderSearch($query, $searchData, $fulfillment = true, $return = false)
    {
        $search = json_decode($searchData, true);
        if (!empty($search['sortby'])) {
            $query->orderBy($search['sortby'], 'desc');
        }
        if (!empty($search['fulfillment']) && $fulfillment == true) {
            $query->whereIn('order_shipping_status', $search['fulfillment']);
        }
        if (!empty($search['keywords'])) {
            static::searchByKeyword($query, $search['keywords'], $return);
        }
        if (!empty($search['dateRange']) && !in_array(null, $search['dateRange'])) {
            $startDate = date('Y-m-d', strtotime($search['dateRange'][0]));
            $endDate = date('Y-m-d', strtotime($search['dateRange'][1]));
            $query->whereBetween('order_date_added', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        }
        if (!empty($search['payment_status'])) {
            if (($key = array_search('cod', $search['payment_status'])) !== false) {
                $query->where('order_payment_method', 'cod');
                unset($search['payment_status'][$key]);
            }

            if (count($search['payment_status']) > 0) {
                $query->whereIn('order_payment_status', $search['payment_status']);
            }
        }
        return $query;
    }
    public static function searchByKeyword($query, $keywords, $return = false)
    {
        $fieldConditions = "";
        $escapeWords = array("in", "it", "a", "the", "of", "or", "i", "you", "he", "me", "us", "they", "she", "to", "but", "that", "this", "those", "then");
        $totalWordInStrings = count($keywords);
        $processWords = 1;
        foreach ($keywords as $val) {
            $fieldConditions .= "( case when user_fname LIKE '%" . $val . "%' then 2 else 0 end )  +
                    ( case when user_lname LIKE '%" . $val . "%' then 2 else 0 end ) +
                    ( case when order_id LIKE '%" . $val . "%' then 1 else 0 end ) +
                    ( case when op_product_name LIKE '%" . $val . "%' then 4 else 0 end ) +
                     ( case when otag_name LIKE '%" . $val . "%' then 3 else 0 end )";
            if ($totalWordInStrings > $processWords) {
                $fieldConditions .= '+';
                $processWords++;
            }
        }

        if ($return == false) {
            $query->leftJoin('order_products', 'order_products.op_order_id', 'orders.order_id');
        }
        $query->leftJoin('order_tags', 'order_tags.otag_order_id', 'orders.order_id');
        static::searchConditions($query, $keywords, $escapeWords);
        $query->selectRaw($fieldConditions . 'as relevancy');
        $query->orderBy('relevancy', 'desc');
        if ($return == false) {
            $query->groupBy('order_id');
        } else {
            $query->groupBy('orrequest_id');
        }

        return $query;
    }

    public static function searchConditions($query, $values, $escapeWords = array())
    {
        $query->where(function ($condistionQuery) use ($values, $escapeWords) {
            foreach ($values as $keyword) {
                if (!in_array($keyword, $escapeWords)) {
                    $keyword  = str_replace(' ', '', str_replace('#', '', $keyword));

                    $condistionQuery->orWhere("user_fname", "LIKE", '%' . $keyword . '%')
                        ->orWhere("user_lname", "LIKE", '%' . $keyword . '%')
                        ->orWhere('order_id', $keyword)
                        ->orWhereRaw("REPLACE(`op_product_name`, ' ', '') LIKE ?", ['%' . $keyword . '%'])
                        ->orWhere("otag_name", "LIKE", '%' . $keyword . '%');
                }
            }
        });

        return $query;
    }
    public static function getCount($statusId)
    {
        return Order::where('order_shipping_status', $statusId)
            ->whereNotIn('order_status', [self::ORDER_STATUS_RETURNED, self::ORDER_STATUS_COMPLETED])->count();
    }

    public static function getRecordsByUserId($userId, $type, $orderid = '', $row = 0)
    {
        $data = [];
        $query = Order::select(
            'order_id',
            'order_status',
            'order_net_amount',
            'order_date_added',
            'order_shipping_status',
            'order_currency_symbol as currency_symbol',
            'order_currency_value',
            'order_shipping_value',
            'order_payment_status as payment_status',
            'order_shipping_methods',
            'order_gift_amount',
            'order_amount_charged',
            'order_tax_charged',
            'order_date_updated',
            'order_discount_coupon_code',
            'order_notes',
            'order_reward_amount',
            'order_payment_method',
            'order_shipping_type',
            'order_discount_amount'
        )->with('products.cancelRequest:orrequest_id,orrequest_op_id,orrequest_status,orrequest_type')
            ->with('products.urlRewrite')
            ->with('products.images:afile_record_id,afile_updated_at')
            ->with(['products.productReview' => function ($rSql) use ($userId) {
                $rSql->select('preview_rating', 'preview_prod_id')->where(['preview_user_id' => $userId, 'preview_publish' => config('constants.YES')]);
            }])->with(['transaction' => function ($txnSql) {
                $txnSql->select('txn_gateway_type', 'txn_order_id', 'txn_gateway_transaction_id', 'txn_gateway_response')->where('txn_type', Transaction::DEBIT_TYPE);
            }])->with(['address' => function ($sql) {
                $sql->orderBy('oaddr_type', 'DESC');
            }])
            ->with('address.country');
        if ($type == 'active') {
            $query->where('order_status', '!=', self::ORDER_STATUS_COMPLETED)
                ->where('order_shipping_status', '!=', self::SHIPPING_STATUS_DELIVERED);
        } elseif ($type == 'history') {
            $query->where(function ($wSql) {
                $wSql->where('order_status', self::ORDER_STATUS_COMPLETED)
                    ->orWhere('order_shipping_status', self::SHIPPING_STATUS_DELIVERED);
            });
        }
        $query->with('additionInfo')
            ->withCount('returnProducts')
            ->withCount('invoice')
            ->where('order_user_id', $userId);
        $query->withCount(['products as digital_products' => function ($sql) {
            $sql->where('op_product_type', Product::DIGITAL_PRODUCT);
        }]);

        if ($orderid) {
            $query->where('order_id', $orderid);
        }
        $query->latest('order_id');
        if ($row != 0) {
            return $query->skip($row)->take(config('app.pagination'))->get();
        } else {
            return $query->paginate(config('app.pagination'));
        }
    }


    public static function getRecordById($id, $userId = 0, $returnId = 0, $returnOrderAddress = false)
    {
        $query = Order::with('additionInfo:*')->with('products.tax')->with('products.rewards')->with('bankInformation')->with(['products.cancelRequest' => function ($returnSql) use ($returnId) {
            $returnSql->join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'orrequest_id')
                ->leftJoin('transactions', 'orrequest_id', 'transactions.txn_orrequest_id');
            $returnSql->select('orrequest_date', 'orrequest_status', 'orrequest_op_id', 'orrequest_id', 'orrequest_type', 'orrequest_qty', 'oramount_tax', 'oramount_shipping', 'oramount_discount', 'oramount_giftwrap_price', 'orrequest_reason', 'orrequest_comment', 'txn_gateway_transaction_id', 'oramount_reward_price');
            if ($returnId != 0) {
                $returnSql->where('orrequest_id', $returnId);
            }
        }])->with('products.images:afile_record_id,afile_updated_at')->with('products.urlRewrite')->with(['products' => function ($products) {
            $products->leftJoin('order_product_addition_info as additioninfo', 'additioninfo.opainfo_op_id', 'op_id')
                ->select('op_order_id', 'op_id', 'op_qty', 'op_product_name', 'op_return_age', 'op_product_type', 'op_product_id', 'op_pov_code', 'op_product_price', 'op_product_variants', 'opainfo_gift_amount', 'opainfo_discount_amount', 'opainfo_shipping_amount', 'opainfo_reward_rate', 'opainfo_cat_tax_code', 'opainfo_tgtype_rate');
        }])
        ->with(['address' => function ($sql) use ($returnOrderAddress) {
            if ($returnOrderAddress == true) {
                $sql->where('oaddr_type', '!=', OrderAddress::BILLING_ADDRESS_TYPE);
            }
            $sql->orderBy('oaddr_type', 'DESC');
        }])->with('address.country')->with(['transaction' => function ($txnSql) {
            $txnSql->select('txn_gateway_type', 'txn_order_id', 'txn_gateway_transaction_id', 'txn_gateway_response')->where('txn_type', Transaction::DEBIT_TYPE);
        }])->leftjoin('users', 'users.user_id', 'orders.order_user_id')
            ->leftJoin('packages', 'packages.pkg_slug', 'orders.order_payment_method')
            ->select(
                'order_id',
                'order_status',
                'user_fname',
                'user_lname',
                'user_email',
                'user_phone_country_id',
                'user_phone',
                'order_net_amount',
                'order_date_added',
                'order_shipping_status',
                'order_currency_symbol as currency_symbol',
                'order_currency_code',
                'order_currency_value',
                'order_shipping_value',
                'order_payment_status as payment_status',
                'order_shipping_methods',
                'order_amount_charged',
                'order_tax_charged',
                'order_date_updated',
                'order_discount_coupon_code',
                'order_notes',
                'order_gift_amount',
                'order_shipping_type',
                'order_reward_amount',
                'user_id',
                'order_user_id',
                'order_payment_method as paymentslug',
                'order_discount_amount',
                'order_pending_rewards',
                DB::raw("(
                CASE
                    WHEN order_payment_status = " . self::PAYMENT_PAID . " THEN '" . Order::PAYMENT_STATUS[self::PAYMENT_PAID] . "' 
                    ELSE '" . Order::PAYMENT_STATUS[self::PAYMENT_PENDING] . "' END
                ) as order_payment_status"),
                DB::raw("CONCAT(user_fname, ' ', user_lname) as username")
            )->selectRaw('coalesce(`pkg_name`, `order_payment_method`) as order_payment_method');
        if ($userId != 0) {
            $query->where('order_user_id', $userId);
        }
        $query->withCount('returnProducts')
        ->withCount(['returnProducts as pending_requests' => function ($rSql) {
            $rSql->whereNotIn('orrequest_status', [OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED, OrderReturnRequest::RETURN_REQUEST_STATUS_DECLINED]);
        }]);
        $query->withCount('invoice');
        return $query->withCount(['products as digital_products' => function ($sql) {
            $sql->where('op_product_type', Product::DIGITAL_PRODUCT);
        }])->where('order_id', $id)->first();
    }
    public function bankInformation()
    {
        return $this->belongsTo('App\Models\OrderReturnBankInfo', 'order_id', 'orbinfo_order_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'op_order_id', 'order_id');
    }
    public function invoice()
    {
        return $this->belongsTo('App\Models\OrderInvoice', 'order_id', 'oinv_order_id');
    }
    public function additionInfo()
    {
        return $this->belongsTo('App\Models\OrderAdditionInfo', 'order_id', 'oainfo_order_id');
    }
    public function orderInvoice()
    {
        return $this->belongsTo('App\Models\OrderProductTaxLog', 'order_id', 'optl_order_id');
    }
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction', 'order_id', 'txn_order_id');
    }
    public function address()
    {
        return $this->hasMany('App\Models\OrderAddress', 'oaddr_order_id', 'order_id');
    }
    public function returnProducts()
    {
        return $this->hasMany('App\Models\OrderReturnRequest', 'orrequest_order_id', 'order_id');
    }
    public static function getReturnByOrderId($orderId)
    {
        return   OrderProduct::with('tax')->join('order_return_requests as request', 'order_products.op_id', 'request.orrequest_op_id')
            ->join('orders', 'orders.order_id', 'order_products.op_order_id')
            ->select('order_shipping_value', 'op_id', 'op_product_price', 'orrequest_qty', 'orrequest_order_status', 'orrequest_status')
            ->where('order_products.op_order_id', $orderId)->get();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'order_user_id', 'user_id');
    }

    public static function getOrderById($orderId)
    {
        return Order::where('order_id', $orderId)->first();
    }

    public static function exportOrders($request)
    {
        $result = [];
        $query = Order::with(['address' => function ($sql) {
            $sql->select(DB::raw(
                "CONCAT(oaddr_name, ', ', oaddr_email,', ', oaddr_address1,' ',oaddr_address2,', ',oaddr_city, ', ', oaddr_state) as addresses"
            ), "oaddr_order_id");
            $sql->orderBy('oaddr_type', 'DESC');
        }])->join('users', 'users.user_id', 'orders.order_user_id')
            ->select(
                'user_fname',
                'user_lname',
                'user_phone',
                'order_id',
                DB::raw("(
                CASE
                    WHEN order_payment_status = " . self::PAYMENT_PAID . " THEN '" . Order::PAYMENT_STATUS[self::PAYMENT_PAID] . "' 
                    ELSE '" . Order::PAYMENT_STATUS[self::PAYMENT_PENDING] . "' END
                ) as order_payment_status"),
                'order_net_amount',
                'order_net_amount',
                'order_notes',
                'order_date_added'
            );
        if ($request['shipping_status'] != 'all' && $request['type'] == 0) {
            $query->where('order_shipping_status', $request['shipping_status']);
        }
        if ($request['fulfillment_status'] != 'all' && $request['type'] != 1 && $request['type'] != 2) {
            $query->where('order_shipping_type', $request['fulfillment_status']);
        }
        if ($request['payment_status'] != 'all'  && $request['type'] != 1) {
            $query->where('order_payment_status', $request['payment_status']);
        }
        $dateField = 'order_date_added';
        if ($request['type'] == 1 || $request['type'] == 2) {
            $query->join('order_return_requests as req', 'req.orrequest_order_id', 'orders.order_id')
                ->join('order_products', 'order_products.op_id', 'req.orrequest_op_id')
                ->join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'req.orrequest_id');
            $query->addSelect('orrequest_id', 'op_product_name', 'op_product_price', 'orrequest_date', 'orrequest_reason', 'orrequest_comment', 'orrequest_qty', 'oramount_tax', 'oramount_shipping', 'oramount_discount', 'oramount_giftwrap_price', 'oramount_reward_price');
            $query->where('orrequest_type', $request['type']);
            $dateField = 'orrequest_date';
        }
        self::dateRangeFilters($query, $request, $dateField);

        $result['orders'] = $query->latest('order_id')->get();
        return $result;
    }


    public static function exportInvoices($request)
    {
        $query = orderInvoice::select('oinv_content', 'oinv_number', 'oinv_created_at');
        self::dateRangeFilters($query, $request, 'oinv_created_at');
        return $query->latest('oinv_order_id')->get();
    }
    public static function dateRangeFilters($query, $request, $field)
    {
        if ($request['date_status']) {
            switch ($request['date_status']) {
                case 'today':
                    $query->whereDate($field, Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween($field, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth($field, date('m'))
                        ->whereYear($field, date('Y'));
                    break;
                case 'year':
                    $query->whereYear($field, date('Y'));
                    break;
                case 'custom':
                    $explode = explode(',', $request['dateRange']);
                    $query->whereBetween($field, [$explode[0] . " 00:00:00", $explode[1] . " 23:59:59"]);
                    break;
            }
        }
        return $query;
    }

    public static function getOrderAddressByUserId($userId)
    {
        //return Order::select('order_id','order_user_id','order_addresses.*')->join('order_addresses', 'orders.order_id', '=', 'order_addresses.oaddr_order_id')->where('order_user_id', $userId)->get();
        return Order::where('order_user_id', $userId)->get()->pluck('order_id')->toArray();
    }

    public function orderProductSum()
    {
        return $this->hasMany('App\Models\OrderProduct', 'op_order_id', 'order_id')
            ->selectRaw('(SUM(op_product_price*op_qty)) as gross_sale, op_order_id');
    }
    public static function getOrdersForApp($userId, $type, $page, $orderid = '', $lastOrder = false)
    {
        $data = [];
        $query = Order::select(
            'order_id',
            'order_status',
            'order_net_amount',
            'order_date_added',
            'order_shipping_status',
            'order_currency_symbol as currency_symbol',
            'order_currency_value',
            'order_shipping_value',
            'order_payment_status as payment_status',
            'order_shipping_methods',
            'order_gift_amount',
            'order_amount_charged',
            'order_tax_charged',
            'order_date_updated',
            'order_discount_coupon_code',
            'order_notes',
            'order_reward_amount',
            'order_payment_method',
            'order_shipping_type',
            'order_discount_amount'
        )
        ->with('products.cancelRequest:orrequest_id,orrequest_op_id,orrequest_status,orrequest_type')
            ->with(array('products.images' => function ($query) {
                $query->select('afile_record_id', DB::raw("CONCAT('" . url('') . "', '/yokart/image/', afile_id,'?t=', UNIX_TIMESTAMP(afile_updated_at)) as product_image"));
            }))
            ->with(['products.productReview' => function ($rSql) use ($userId) {
                $rSql->select('preview_rating', 'preview_prod_id','preview_id')->where(['preview_user_id' => $userId, 'preview_publish' => config('constants.YES')]);
            }])
            ->with('products')
            ->with(['products' => function ($txnSql) {
                $txnSql->select('*')
                ->withCasts([
                    'op_product_variants' => 'object'
                ]);
            }])
            ->with(['transaction' => function ($txnSql) {
                $txnSql->select('txn_gateway_type', 'txn_order_id', 'txn_gateway_transaction_id', 'txn_gateway_response')
                ->withCasts([
                    'txn_gateway_response' => 'object'
                ])
                ->where('txn_type', Transaction::DEBIT_TYPE);
            }])->with(['address' => function ($sql) {
                $sql->orderBy('oaddr_type', 'DESC');
            }])
            ->with('address.country')
            ->with('bankInformation');
        if ($type == 'active' || empty($type) && !$orderid) {
            $query->where('order_status', '!=', self::ORDER_STATUS_COMPLETED)
               ->where('order_shipping_status', '!=', self::SHIPPING_STATUS_DELIVERED);
        } elseif ($type == 'history' && !$orderid) {
            $query->where(function ($wSql) {
                $wSql->where('order_status', self::ORDER_STATUS_COMPLETED)
                    ->orWhere('order_shipping_status', self::SHIPPING_STATUS_DELIVERED);
            });
        }
        $query->with('additionInfo')
            ->withCount('returnProducts')
            ->withCount('invoice')
            ->where('order_user_id', $userId);
        $query->withCount(['products as digital_products' => function ($sql) {
            $sql->where('op_product_type', Product::DIGITAL_PRODUCT);
        }]);

        if ($orderid) {
            $query->where('order_id', $orderid);
        }
        $query->latest('order_id');
        if ($lastOrder) {
            return $query->first();
        }
        return $query->paginate((int) config('app.app_pagination'), ['*'], 'page', $page);
    }
}
