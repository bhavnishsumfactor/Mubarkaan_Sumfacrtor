<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\ShippingToLocation;

class ShippingProfileZone extends YokartModel
{
    const ALL_WORLD_ZONE_TYPE = 1;
    const SPECIFIC_WORLD_ZONE_TYPE = 0;

    public $timestamps = false;

    protected $fillable = ['spzone_sprofile_id', 'spzone_name', 'spzone_shipping_type'];


    public function locationsGrouped()
    {
        return $this->hasMany('App\Models\ShippingToLocation', 'stloc_spzone_id', 'spzone_id')->groupBy('stloc_country_id');
    }
    public function locations()
    {
        return $this->hasMany('App\Models\ShippingToLocation', 'stloc_spzone_id', 'spzone_id');
    }
    public function rates()
    {
        return $this->hasMany('App\Models\ShippingRate', 'srate_spzone_id', 'spzone_id');
    }
}
