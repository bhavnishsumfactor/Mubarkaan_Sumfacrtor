<?php

namespace App\Models;

use App\Models\YokartModel;

class OptionToVarient extends YokartModel
{
    
    public $timestamps = false;
    protected $fillable = [
        'otv_pov_id', 'otv_opn_id'
    ];

}