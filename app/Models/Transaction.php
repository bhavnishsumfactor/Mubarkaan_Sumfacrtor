<?php

namespace App\Models;

use App\Models\YokartModel;
use Carbon\Carbon;

class Transaction extends YokartModel
{
    const CREDIT_TYPE = 1;
    const DEBIT_TYPE = 2;
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;
    public $timestamps = false;
    protected $fillable = [
        'txn_user_id', 'txn_date', 'txn_amount', 'txn_comments', 'txn_status',
        'txn_order_id', 'txn_type', 'txn_gateway_transaction_id', 'txn_gateway_type', 'txn_gateway_response', 'txn_orrequest_id'
    ];

    public static function createTransaction($orderId, $price, $comment, $userId, $gateWayType, $transId, $response, $type)
    {
        try {
            Transaction::create([
                'txn_user_id' => $userId,
                'txn_date' => Carbon::now(),
                'txn_amount' => $price,
                'txn_comments' => $comment,
                'txn_status' => Transaction::STATUS_COMPLETED,
                'txn_order_id' => $orderId,
                'txn_gateway_type' => $gateWayType,
                'txn_gateway_transaction_id' => $transId,
                'txn_gateway_response' => $response,
                'txn_type' => $type
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
