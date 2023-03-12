<?php

namespace App\Models;

use App\Models\YokartModel;

class OrderProductTaxLog extends YokartModel
{
    public $timestamps = false;
    protected $fillable = ['optl_order_id', 'optl_op_id', 'optl_key', 'optl_value'];

    public static function calculateTax($opId)
    {
        return self::where('optl_op_id',$opId)->where('optl_key', 'Tax')->first();
    }
}
