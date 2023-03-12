<?php

namespace App\Models;

use App\Models\YokartModel;

class OrderReturnAmount extends YokartModel
{
    public const PENDING = 0;
    public const PAID = 1;
    
    public const TYPE = [
        self::PENDING => 'Pending',
        self::PAID => 'Paid'
    ];

     public $timestamps = false;

    protected $primaryKey = 'oramount_id';

    protected $fillable = [
        'oramount_orrequest_id', 'oramount_op_id', 'oramount_tax', 'oramount_shipping',
        'oramount_discount', 'oramount_giftwrap_price', 'oramount_reward_price', 'oramount_payment_status'
    ];
}
