<?php

namespace App\Models;

use App\Models\YokartModel;

class OrderInvoice extends YokartModel
{
    public $timestamps = false;

    protected $fillable = [
        'oinv_order_id', 'oinv_number', 'oinv_content', 'oinv_created_at'
    ];
}
 