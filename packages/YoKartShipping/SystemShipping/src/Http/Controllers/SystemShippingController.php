<?php

namespace YokartShipping\SystemShipping\Http\Controllers;

use App\Http\Controllers\PackageYokartController;
use YokartShipping\SystemShipping\Models\SystemShipping;

class SystemShippingController extends PackageYokartController
{
    public static function getRates($products, $countryId, $stateId)
    {
        $shippingResults = [];
        $total = [];
        foreach ($products as $key => $value) {
            $records = SystemShipping::getShippingByProductId($value['productid'], $countryId, $stateId);
            if (!empty($records)) {
                $shippingResults[$key] = $records;
                continue;
            }
            $records = SystemShipping::getAllShippingByProductId($value['productid']);
            if (!empty($records)) {
                $shippingResults[$key] = $records;
                continue;
            }
            $records = SystemShipping::isProductExist($value['productid']);
            if (!empty($records)) {
                $shippingResults[$key] = ['error' => true];
                continue;
            }
            $records = SystemShipping::getProductBaseShipping($countryId, $stateId);
            if (!empty($records)) {
                $shippingResults[$key] = $records;
                $total['price'][] = round($value['price']);
                $total['weigth'][] = round($value['weigth']);
                continue;
            }
            $records = SystemShipping::getProductBaseAllShipping();
            if (!empty($records)) {
                $shippingResults[$key] = $records;
                $total['price'][] = round($value['price']);
                $total['weigth'][] = round($value['weigth']);
            }
        }
        
        return SystemShipping::applyCondistions($shippingResults, $products, $total);
    }
}
