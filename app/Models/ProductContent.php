<?php

namespace App\Models;

use App\Models\YokartModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductContent extends YokartModel
{
    use HasFactory;
    
    const PRODUCT_WEIGHT_KG = 0;
    const PRODUCT_WEIGHT_GM = 1;

    const PRODUCT_WEIGHT_METRIC_SYSTEM = [
        self::PRODUCT_WEIGHT_KG => 'kg',
        self::PRODUCT_WEIGHT_GM => 'gm'
    ];

    const PRODUCT_WEIGHT_OUNCES = 0;
    const PRODUCT_WEIGHT_POUNDS = 1;

    const PRODUCT_WEIGHT_US_SYSTEM = [
        self::PRODUCT_WEIGHT_OUNCES => 'oz',
        self::PRODUCT_WEIGHT_POUNDS => 'lb'
    ];

    public $primaryKey = null;
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
       'pc_prod_id', 'pc_sku','pc_threshold_stock_level', 'pc_substract_stock','pc_length',
       'pc_weight','pc_weight_unit', 'pc_warranty_age', 'pc_return_age','pc_cancellation_age',
       'pc_isbn','pc_hsn','pc_sac','pc_upc', 'pc_video_link', 'pc_file_title', 'pc_download_validity_in_days',
       'pc_max_download_times','pc_gift_wrap_available', 'pc_upload_additional_files'
    ];

    public static function getWeightOptions()
    {
        $weightSystem = Configuration::getValue('WEIGHT_UNIT');
        if ($weightSystem == 0) {
            $units = self::PRODUCT_WEIGHT_METRIC_SYSTEM;
        } else {
            $units = self::PRODUCT_WEIGHT_US_SYSTEM;
        }
        return $units;
    }
  

}
