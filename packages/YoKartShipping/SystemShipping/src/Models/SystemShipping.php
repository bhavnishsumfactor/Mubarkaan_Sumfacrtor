<?php
namespace YoKartShipping\SystemShipping\Models;

use App\Models\PackageYokartModel;
use App\Models\ShippingProfile;
use App\Models\ShippingProfileZone;
use App\Models\ShippingRate;

class SystemShipping extends PackageYokartModel
{
    public static function getShippingObj($countryId = '', $stateId = '')
    {
        $query = ShippingProfile::join('shipping_profile_zones as zone', 'zone.spzone_sprofile_id', 'shipping_profiles.sprofile_id')
        ->join('shipping_rates as rates', 'rates.srate_spzone_id', 'zone.spzone_id');
        if (!empty($countryId)) {
            $query->join('shipping_to_locations as location', 'location.stloc_spzone_id', 'zone.spzone_id');
            $query->where('stloc_country_id', $countryId);
            if (!empty($stateId)) {
                $query->where('stloc_state_id', $stateId);
            }
        }
        return  $query->select('sprofile_id', 'srate_name', 'srate_cost', 'srate_min_value', 'srate_max_value', 'srate_id', 'srate_type');
    }
    public static function getShippingByProductId($id, $countryId, $stateId)
    {
        $query = static::getShippingObj($countryId, $stateId);
        return $query->join('shipping_profile_to_products as products', 'products.sprod_sprofile_id', 'shipping_profiles.sprofile_id')
        ->where('sprod_prod_id', $id)->get()->toArray();
    }
    public static function getAllShippingByProductId($id)
    {
        $query = static::getShippingObj();
        return $query->join('shipping_profile_to_products as products', 'products.sprod_sprofile_id', 'shipping_profiles.sprofile_id')
        ->where('sprod_prod_id', $id)->where('zone.spzone_shipping_type', ShippingProfileZone::ALL_WORLD_ZONE_TYPE)
        ->get()->toArray();
    }
    public static function isProductExist($id)
    {
        $query = static::getShippingObj();
        return $query->join('shipping_profile_to_products as products', 'products.sprod_sprofile_id', 'shipping_profiles.sprofile_id')
        ->where('sprod_prod_id', $id)->count();
    }
    public static function getProductBaseShipping($countryId, $stateId)
    {
        $query = static::getShippingObj($countryId, $stateId);
        return $query->where('sprofile_id', ShippingProfile::DEFAULT_SHIPPING_ID)->get()->toArray();
    }

    public static function getProductBaseAllShipping()
    {
        $query = static::getShippingObj();
        $query->where('zone.spzone_shipping_type', ShippingProfileZone::ALL_WORLD_ZONE_TYPE);
        return $query->where('sprofile_id', ShippingProfile::DEFAULT_SHIPPING_ID)->get()->toArray();
    }
    public static function applyCondistions($shippingRecords, $products, $total)
    {
        $calculatedShipping = [];
        $shippingConditions = [];
        foreach ($shippingRecords as $key => $records) {
            $productName = $products[$key]['name'];
            if (key($records) === 'error') {
                $calculatedShipping[$key . '_error'] = $records;
                $calculatedShipping[$key . '_error']['labels'][] = $key;
                continue;
            }
            $price = $products[$key]['price'];
            $weigth = $products[$key]['weigth'];
            if (count($total) > 0) {
                $price = array_sum($total['price']);
                $weigth = array_sum($total['weigth']);
            }
          
            foreach ($records as $value) {
                if ($value['srate_type'] == ShippingRate::PRICE_CONDITION) {
                    if ($price >= $value['srate_min_value'] && $price <= $value['srate_max_value']) {
                        $calculatedShipping = static::assignValues($calculatedShipping, $value, $productName, $key);
                        $shippingConditions[$key][] = $value['srate_cost'];
                    }
                } elseif ($value['srate_type'] == ShippingRate::WEIGHT_CONDITION) {
                    if ($weigth >= $value['srate_min_value'] && $weigth <= $value['srate_max_value']) {
                        $calculatedShipping = static::assignValues($calculatedShipping, $value, $productName, $key);
                        $shippingConditions[$key][] = $value['srate_cost'];
                    }
                } else {
                    $calculatedShipping = static::assignValues($calculatedShipping, $value, $productName, $key);
                }
            }
        }
       
        return static::resultArray($calculatedShipping, $shippingConditions);
    }
    public static function assignValues($calculatedShipping, $value, $productName, $productId)
    {
        $arrayKey = $value['srate_id'];
        $defaultShipping = 1;
        if ($value['sprofile_id'] != ShippingProfile::DEFAULT_SHIPPING_ID) {
            $arrayKey =  $productId . '-' . $value['srate_id'];
            $defaultShipping = 0;
        }
        $calculatedShipping[$arrayKey]['labels'][] = $productId;
        $calculatedShipping[$arrayKey]['name'] = $value['srate_name'];
        $calculatedShipping[$arrayKey]['order_level_ship'] = $defaultShipping;
        $calculatedShipping[$arrayKey]['price'] = $value['srate_cost'];
        $calculatedShipping[$arrayKey]['productId'] = $productId;
        return $calculatedShipping;
    }
    public static function resultArray($calculatedShipping, $shippingConditions)
    {
        $resultArray = [];
        foreach ($calculatedShipping as $key => $value) {
            $arrayLabels = implode(',', $value['labels']);
            if (isset($value['error'])) {
                $resultArray[$arrayLabels][$key]['error'] = true;
                $resultArray['error'] = true;
                continue;
            }
            if (isset($shippingConditions[$value['productId']])) {
                $maxprice = max($shippingConditions[$value['productId']]);
                if ($maxprice != $value['price']) {
                    continue;
                }
            }
            $resultArray[$arrayLabels][$key]['name'] = $value['name'];
            $resultArray[$arrayLabels][$key]['price'] = $value['price'];
            $resultArray[$arrayLabels][$key]['order_level_ship'] = $value['order_level_ship'];
        }
        return $resultArray;
    }
}
