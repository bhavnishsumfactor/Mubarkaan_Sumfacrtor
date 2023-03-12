<?php

namespace App\Helpers;

use App\Helpers\YokartHelper;
use App\Models\Package;

class TaxHelper extends YokartHelper
{
    protected $activePackage;
    protected $tax;
    protected $state;
    protected $country;

    public function __construct($country, $state)
    {
        $this->country = $country;
        $this->state = $state;
        $this->activePackage = Package::getActivePackage('tax');
        $this->tax = 'YoKartTax\\'.$this->activePackage->pkg_slug.'Management\Http\Controllers\\'.$this->activePackage->pkg_slug.'Controller';
    }

    public function getRates($taxId)
    {
     
        $tax =  $this->tax::price($taxId, $this->country, $this->state);
        $value = 0;
        if (!empty($tax)) {
            $value = $tax['value'];
        }
        return ['type' => 'tax','value' => $value];
    }
}
