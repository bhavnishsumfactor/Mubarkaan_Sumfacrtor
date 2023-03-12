<?php

namespace App\Models;

use App\Models\YokartModel;

class OrderProductCharge extends YokartModel
{
    public $timestamps = false;

	protected $fillable = ['opc_op_id','opc_type', 'opc_value'];

    public static function calculateTax($opId, $qty)
    {
        $orderProductTax = OrderProductCharge::where('opc_type', 'tax')->where('opc_op_id' , $opId)->first();
        if(isset($orderProductTax->opc_value) && !empty($orderProductTax->opc_value)) {
            return ($orderProductTax->opc_value * $qty);
        }
        return 0;
    }
}
