<?php

namespace App\Models;

use App\Models\YokartModel;

class SpecialPriceExclude extends YokartModel
{
    public $timestamps = false;
    protected $fillable = [
      'spe_splprice_id', 'spe_record_id', 'spe_subrecord_id'
    ];

    public function varients()
    {
        return $this->hasMany('App\Models\ProductOptionVarient', 'pov_code', 'spe_subrecord_id');
    }
}
