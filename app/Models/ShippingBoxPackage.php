<?php

namespace App\Models;

use App\Models\YokartModel;

class ShippingBoxPackage extends YokartModel
{
    protected $primaryKey = 'sbpkg_id';

    const DIMENSION_CENTIMETER = 0;
    const DIMENSION_METER = 1;

    const DIMENSION_METRIC_SYSTEM = [
        self::DIMENSION_CENTIMETER => 'cm',
        self::DIMENSION_METER => 'm'
    ];

    const DIMENSION_INCH = 0;
    const DIMENSION_FOOT = 1;

    const DIMENSION_US_SYSTEM = [
        self::DIMENSION_INCH => 'in',
        self::DIMENSION_FOOT => 'ft'
    ];
       
    public $timestamps = false;
    
    protected $fillable = ['sbpkg_name', 'sbpkg_length', 'sbpkg_width', 'sbpkg_heigth', 'sbpkg_dimension_type', 'sbpkg_default'];

    public static function getDimensionOptions()
    {        
        $dimensionSystem = Configuration::getValue('DIMENSION_UNIT');
        if ($dimensionSystem==0) {
            $units = self::DIMENSION_METRIC_SYSTEM;
        } else {
            $units = self::DIMENSION_US_SYSTEM;
        }
        return $units;
    }
}
