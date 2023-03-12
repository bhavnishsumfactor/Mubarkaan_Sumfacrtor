<?php

namespace App\Models;

use App\Models\YokartModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSpecification extends YokartModel
{
    use HasFactory;
    
     public $timestamps = false;
     protected $fillable = [
        'ps_product_id', 'ps_product_key', 'ps_product_value', 'ps_product_group'
    ];

    public static function recordsByProductId($productId){
        $records = ProductSpecification::where('ps_product_id', $productId)->get();
        return self::specificationHierarchy($records);
    }
    public static function specificationHierarchy($records)
    {
        $specificationsArray = array();
        foreach ($records as $record) {
            $specificationsArray[$record->ps_product_group]['key'][] = $record->ps_product_key;
            $specificationsArray[$record->ps_product_group]['value'][] = $record->ps_product_value;
        }

        return $specificationsArray;
    }
    public static function specificationHierarchyForApp($records)
    {
        $specificationsArray = [];
        foreach ($records as $key => $record) {
            $temp = [];
            $groupName = $record->ps_product_group;
            $groupColArr = array_column($specificationsArray, 'group');
            $groupExists = array_search($groupName, $groupColArr);
                
            $tempPair = [
                'key' => $record->ps_product_key,
                'value' => $record->ps_product_value
            ];
            $temp['group'] = $groupName;
            $temp['specs'][] = $tempPair;
            if ($groupExists !== false && isset($specificationsArray[$groupExists])) {
                $specificationsArray[$groupExists]['specs'][] = $tempPair;
            } else {
                $specificationsArray[] = $temp;
            }            
        }
        $groupExists = array_search("", array_column($specificationsArray, 'group'));
        $newArr = [];
        if(isset($specificationsArray[$groupExists])){
            $newArr[] = $specificationsArray[$groupExists];
        }
        unset($specificationsArray[$groupExists]);
        return array_merge($newArr, array_values($specificationsArray));
    }
}
