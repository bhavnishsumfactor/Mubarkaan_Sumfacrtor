<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Order;
use App\Models\User;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Str;
use DB;

class OrderReturnRequest extends YokartModel
{
    public const RETURN_REQUEST_TYPE_REPLACE = 1;
    public const RETURN_REQUEST_TYPE_REFUND = 2;

    public const RETURN_REQUEST_STATUS_PENDING = 0;
    public const RETURN_REQUEST_STATUS_RECEIVED = 1;
    public const RETURN_REQUEST_STATUS_REFUNDED = 2;
    public const RETURN_REQUEST_STATUS_DECLINED = 3;


    public const REQUEST_STATUS = [
        self::RETURN_REQUEST_STATUS_PENDING => 'pending',
        self::RETURN_REQUEST_STATUS_RECEIVED => 'item-received',
        self::RETURN_REQUEST_STATUS_REFUNDED => 'approved',
        self::RETURN_REQUEST_STATUS_DECLINED => 'declined',
    ];

    public const RETURN = 1;
    public const CANCELLATION = 2;

    public const TYPE = [
        self::RETURN => 'Return',
        self::CANCELLATION => 'Cancellation'
    ];

    public $timestamps = false;

    protected $primaryKey = 'orrequest_id';

    protected $fillable = [
        'orrequest_user_id', 'orrequest_op_id', 'orrequest_order_id', 'orrequest_qty',
        'orrequest_date', 'orrequest_status', 'orrequest_omsg_id', 'orrequest_order_status',
        'orrequest_txn_gateway_transaction_id', 'orrequest_reason', 'orrequest_comment', 'orrequest_type'
    ];


    public static function getCount($statusId, $type)
    {
        return Order::join('order_return_requests as req', 'req.orrequest_order_id', 'orders.order_id')
            ->select('order_id')->where('orrequest_status', $statusId)->where('order_status', $type)->groupBy('order_id')->get()->count();
    }
    public static function flipedStatus()
    {
        return array_flip(self::REQUEST_STATUS);
    }
    public function product()
    {
        return $this->hasOne('App\Models\OrderProduct', 'op_id', 'orrequest_op_id');
    }
    public static function getRecordById($requestId)
    {
        return OrderReturnRequest::join('order_products as op', 'order_return_requests.orrequest_op_id', 'op.op_id')
            ->join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'orrequest_id')
            ->select('orrequest_status', 'op.op_product_price', 'oramount_tax', 'oramount_shipping', 'oramount_discount', 'oramount_reward_price', 'orrequest_type', 'oramount_giftwrap_price', 'oramount_payment_status', 'oramount_id', 'orrequest_order_id', 'orrequest_user_id', 'orrequest_qty', 'op_product_id', 'orrequest_op_id', 'orrequest_order_status', 'op_pov_code', 'orrequest_comment', 'orrequest_id', 'orrequest_reason')->with('product.rewards')->with(['product' => function ($pSql) {
                $pSql->select('op_order_id', 'op_id', 'op_qty', 'op_product_id', 'op_pov_code');
            }])->where('orrequest_id', $requestId)->first();
    }
    public static function flipedType()
    {
        return array_flip(self::TYPE);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'orrequest_user_id', 'user_id');
    }

    /*public static function getReturnAmountByOrderProductId($orderProductId, $orderProductPrice)
    {
        $orderReturnQuantity = OrderReturnRequest::select(['orrequest_qty'])->where('orrequest_op_id', $orderProductId)->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED)->first();
        if(isset($orderReturnQuantity) && !empty($orderReturnQuantity)) {
            return ($orderProductPrice*$orderReturnQuantity->orrequest_qty);
        }
        return 0;
    }*/

    public static function getReturnDataOrderProductId($orderProductId, $orderProductPrice)
    {
        $orderReturnQuantity = OrderReturnRequest::join('order_return_amounts as ramount', 'ramount.oramount_orrequest_id', 'orrequest_id')
        ->select(['orrequest_qty', 'oramount_tax', 'oramount_shipping', 'oramount_discount', 'orrequest_order_id'])->where('orrequest_op_id', $orderProductId)->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED)->first();
        if (isset($orderReturnQuantity) && !empty($orderReturnQuantity)) {
            $orderReturnRequests = OrderReturnRequest::where('orrequest_order_id', $orderReturnQuantity->orrequest_order_id);
            $totalReturnRequestCount = $orderReturnRequests->count();
            $orderReturnRequestRefundedCount = $orderReturnRequests->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED)->count();
            if ($orderReturnRequestRefundedCount == $totalReturnRequestCount) {
                $shippingAmount = $orderReturnQuantity->oramount_shipping;
            } else {
                $shippingAmount = 0;
            }
            $data['price'] = ($orderProductPrice * $orderReturnQuantity->orrequest_qty);
            $data['quantity'] = $orderReturnQuantity->orrequest_qty;
            $data['tax'] = $orderReturnQuantity->oramount_tax;
            $data['shipping'] = $shippingAmount;
            $data['discount'] = $orderReturnQuantity->oramount_discount;
            return $data;
        }
        return [];
    }

    public static function getReturnQuantityByOrderId($orderId)
    {
        return OrderReturnRequest::select(DB::raw("coalesce(SUM(orrequest_qty), 0) as quantity"))->where('orrequest_order_id', $orderId)->where('orrequest_status', OrderReturnRequest::RETURN_REQUEST_STATUS_REFUNDED)->first();
    }

    public static function replacementCancellation($type, $order, $replacementData, $item, $adminName="", $requestId= 0, $user)
    {
        $op = OrderProduct::where('op_id', $item['op_id'])->first();
        $variants = json_decode($op->op_product_variants, true);
        if (!empty($variants["styles"])) {
            $variants = " (" . implode(" | ", $variants["styles"]) . ") ";
        } else {
            $variants = "";
        }
        $type = str_replace('{adminUser}', $adminName, $type);
        $type = str_replace('{userName}', $user->user_fname . ' ' . $user->user_lname, $type);
        $type = str_replace('{orderId}', $order->order_id, $type);
        $type = str_replace('{productName}', $op->op_product_name .' X '. $op->op_qty, $type);
        $type = str_replace('{productImage}', productImageUrl($op->op_product_id, $op->op_pov_code), $type);
        $type = str_replace('{productUrl}', getRewriteUrl("products", $op->op_product_id), $type);
        $type = str_replace('{productPrice}', formatPriceByCurrencyCode($op->op_product_price, $order->order_currency_code), $type);
        $type = str_replace('{cancellationReason}', $replacementData['cancellationReason'], $type);
        $type = str_replace('{requestType}', $replacementData['requestType'], $type);
        $type = str_replace('{cancellationAdditionInformation}', $replacementData['additionInfoMessage'], $type);
        $type = str_replace('{subtotal}', formatPriceByCurrencyCode($replacementData['subTotal'], $order->order_currency_code), $type);
        $type = str_replace('{tax}', formatPriceByCurrencyCode($replacementData['tax'], $order->order_currency_code), $type);
        $type = str_replace('{shipping}', formatPriceByCurrencyCode($replacementData['shippingAmount'], $order->order_currency_code), $type);
        $type = str_replace('{totalRefundAmount}', formatPriceByCurrencyCode($replacementData['refundableAmount'], $order->order_currency_code), $type);
        $type = str_replace('{reviewRequestUrl}', url("/admin#/order/return/" . $order->order_id . "/". $requestId), $type);
        $type = str_replace('{cancellationRequestId}', $order->order_id . "-". $requestId, $type);
        $type = str_replace('{returnRequestId}', $order->order_id . "-". $requestId, $type);
        $type = str_replace('{websiteName}', env('APP_NAME'), $type);
        $type = str_replace('{RequestId}', $requestId, $type);
        $type = str_replace('{requestDeclinedComment}', '', $type);

        $configurations = Configuration::getRecordsByPrefix('BUSINESS');
        $countryName = '';
        if (isset($configurations['BUSINESS_COUNTRY']) && !empty($configurations['BUSINESS_COUNTRY'])) {
            $country = Country::getRecordById($configurations['BUSINESS_COUNTRY']);
            $countryName = $country->country_name;
        }
        $stateName = '';
        if (isset($configurations['BUSINESS_STATE']) && !empty($configurations['BUSINESS_STATE'])) {
            $state = State::getRecordById($configurations['BUSINESS_STATE']);
            $stateName = $state->state_name;
        }
        $type = str_replace('{adminAddress1}', ($configurations['BUSINESS_ADDRESS1'] ?? ''), $type);
        $type = str_replace('{adminAddress2}', ($configurations['BUSINESS_ADDRESS2'] ?? ''), $type);
        $type = str_replace('{adminAddressState}', $stateName, $type);
        $type = str_replace('{adminAddressCountry}', $countryName, $type);
        $type = str_replace('{adminAddressPincode}', ($configurations['BUSINESS_PINCODE'] ?? ''), $type);
        return $type;
    }
}
