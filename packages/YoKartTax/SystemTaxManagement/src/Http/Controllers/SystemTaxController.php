<?php

namespace YoKartTax\SystemTaxManagement\Http\Controllers;

use App\Http\Controllers\PackageYokartController;
use YoKartTax\SystemTaxManagement\Models\SystemTax;

class SystemTaxController extends PackageYokartController
{
    public static function price($taxId, $countryId, $stateId)
    {
        return SystemTax::taxCalculate($taxId, $countryId, $stateId);
    }
}
