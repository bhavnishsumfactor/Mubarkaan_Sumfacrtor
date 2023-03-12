<?php

namespace App\Models;

use App\Models\YokartModel;
use Carbon\Carbon;
use Session;

class ProductStockHold extends YokartModel
{
    public $timestamps = false;
    protected $fillable = [
        'pshold_prod_id', 'pshold_session_id', 'pshold_prod_stock', 'pshold_pov_code', 'pshold_added_on'
    ];
    public static function ValidateHoldStock($prodId, $variantCode = 0, $cartSession = "")
    {
        if (empty($cartSession)) {
            $cartSession = Session::get('cartSession');
        }
        ProductStockHold::where('pshold_prod_id', $prodId)->where('pshold_session_id', '!=', $cartSession)->where('pshold_added_on', '<=', Carbon::now()->subMinutes(config('app.expiredToken'))->toDateTimeString())->delete();
        $obj = ProductStockHold::where('pshold_prod_id', $prodId)->where('pshold_session_id', '!=', $cartSession);
        if ($variantCode != 0) {
            $obj->where('pshold_pov_code', $variantCode);
        }
        return $obj->sum('pshold_prod_stock');
    }
    public static function storeProductOnHold($prodId, $qty, $sum = true, $variantCode = 0, $cartSession = "")
    {
        if (empty($cartSession)) {
            $cartSession = Session::get('cartSession');
        }
        $holdArray = [
            'pshold_prod_id' => $prodId,
            'pshold_session_id' => $cartSession,
            'pshold_prod_stock' => $qty,
            'pshold_pov_code' => $variantCode,
            'pshold_added_on' => Carbon::now(),
        ];

        $obj = ProductStockHold::select('pshold_id', 'pshold_prod_stock')->where('pshold_prod_id', $prodId)->where('pshold_session_id', $cartSession);
        if ($variantCode != 0) {
            $obj->where('pshold_pov_code', $variantCode);
        }
        $exist = $obj->first();
        if (!empty($exist)) {
            if ($sum == true) {
                $holdArray['pshold_prod_stock'] = $qty + $exist->pshold_prod_stock;
            }
            ProductStockHold::where('pshold_id', $exist->pshold_id)->update($holdArray);
        } else {
            ProductStockHold::create($holdArray);
        }
    }
}
