<?php

namespace App\Models;

use App\Models\YokartModel;

class ShippingRate extends YokartModel
{
    const WEIGHT_CONDITION = 1;
    const PRICE_CONDITION = 2;

    const RATE_CONDITIONS = [
        self::WEIGHT_CONDITION => 'weight',
        self::PRICE_CONDITION  => 'price'
        ];
    public $timestamps = false;
    protected $fillable = ['srate_spzone_id', 'srate_type', 'srate_name', 'srate_cost', 'srate_min_value', 'srate_max_value'];
}
