<?php

namespace App\Models;

use App\Models\YokartModel;

class OrderTag extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'otag_id';

    protected $fillable = [
        'otag_order_id', 'otag_name'
    ];
}
