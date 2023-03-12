<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\ShippingProfileZone;

class ShippingToLocation extends YokartModel
{
    public $timestamps = false;

    protected $fillable = ['stloc_spzone_id', 'stloc_country_id', 'stloc_state_id'];

    public function states()
    {
        return $this->belongsTo('App\Models\State', 'stloc_state_id', 'state_id');
    }
    public function zone()
    {
        return $this->belongsTo('App\Models\ShippingProfileZone', 'stloc_spzone_id', 'spzone_id');
    }
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'stloc_country_id', 'country_id');
    }
    public static function getSelectedLocations($id)
    {
        return ShippingProfileZone::join('shipping_to_locations as stloc', 'shipping_profile_zones.spzone_id', 'stloc.stloc_spzone_id')
        ->where('shipping_profile_zones.spzone_sprofile_id', $id)
        ->pluck('stloc.stloc_country_id', 'stloc.stloc_state_id')->toArray();
    }
}
