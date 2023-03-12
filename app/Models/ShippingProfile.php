<?php
namespace App\Models;

use App\Models\YokartModel;

class ShippingProfile extends YokartModel
{
    const DEFAULT_SHIPPING_ID = 1;

    public $timestamps = false;
    protected $fillable = ['sprofile_name'];

    public function zones(){
        return $this->hasMany('App\Models\ShippingProfileZone', 'spzone_sprofile_id', 'sprofile_id');
    }
    public function products(){
        return $this->hasMany('App\Models\ShippingProfileToProduct', 'sprod_sprofile_id', 'sprofile_id');
    }

    static public function getCount(){
        return ShippingProfile::has('zones')->withCount('zones')->get();
    }
}
