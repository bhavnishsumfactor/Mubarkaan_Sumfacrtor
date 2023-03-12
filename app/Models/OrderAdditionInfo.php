<?php

namespace App\Models;

use App\Models\YokartModel;

class OrderAdditionInfo extends YokartModel
{
    public $timestamps = false;
    protected $table = 'order_addition_info';

    protected $fillable = ['oainfo_order_id','oainfo_received_confirmation', 'oainfo_courier_name', 'oainfo_tracking_id'];

    public static function getTrackingIDAndCourierFromOrder($orderId)
    {
        //return OrderAdditionInfo::select('oainfo_courier_name', 'oainfo_tracking_id')->where('oainfo_order_id', $orderId)->first();
        return OrderAdditionInfo::select('oainfo_courier_name', 'oainfo_tracking_id')->where('oainfo_order_id', $orderId)->first();
    }
}
