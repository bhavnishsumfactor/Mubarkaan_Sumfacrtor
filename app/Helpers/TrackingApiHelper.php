<?php

namespace App\Helpers;

use App\Helpers\YokartHelper;

class TrackingApiHelper extends YokartHelper
{
    protected $tracking;
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
        $this->tracking = 'YoKartShipping\\'.$type.'\Http\Controllers\\'.$type.'Controller';
    }

    public function trackingCouriers()
    {
        return $this->tracking::getTrackingCouriers();
    }

    public function getTrackingInfo($trackingId, $courierCode, $orderId)
    {
        return $this->tracking::getTrackingInfo($trackingId, $courierCode, $orderId);
    }
}