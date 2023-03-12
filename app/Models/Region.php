<?php

namespace App\Models;

use App\Models\YokartModel;

class Region extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'region_id';


    public function country()
    {
        return $this->hasMany('App\Models\Country', 'country_region_id', 'region_id');
    }
}
