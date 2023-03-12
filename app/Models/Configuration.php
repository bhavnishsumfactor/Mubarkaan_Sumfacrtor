<?php

namespace App\Models;

use App\Models\YokartModel;

class Configuration extends YokartModel
{
    public $timestamps = false;
    
    protected $fillable = [
      'conf_name', 'conf_val'
    ];

    public static function getValue($name)
    {
        return Configuration::where('conf_name', $name)->pluck('conf_val')->first();
    }

    public static function getKeyValues($name = array())
    {
        return Configuration::whereIN('conf_name', $name)->pluck('conf_val', 'conf_name')->toArray();
    }
    
    public static function getRecordsByPrefix($prefix, $active = false)
    {
        $configobj =  Configuration::where('conf_name', 'like', $prefix . '%');
        if ($active == true) {
            $configobj->where('conf_val', 1);
        }
        return $configobj->pluck('conf_val', 'conf_name');
    }

    public static function saveValue($name, $val)
    {
        $configuration = Configuration::where('conf_name', $name)->count();
  
        if ($configuration > 0) {
            Configuration::where('conf_name', $name)->update(['conf_val' => ($val ?? '')]);
        } else {
            Configuration::create([
              'conf_name' => $name,
              'conf_val' => $val
            ]);
        }
        \Cache::forget('configuration');
    }

    public static function bulkUpdate($inputData)
    {
        foreach ($inputData as $name => $val) {
            $data = Configuration::saveValue($name, ($val ?? ''));
        }
    }

    public static function getDefaultSettings()
    {
        $keys = [
            'SYSTEM_MESSAGE_CLOSE_TIME',
            'SYSTEM_MESSAGE_STATUS',
            'SYSTEM_MESSAGE_POSITION'
        ];
        return json_encode(self::getKeyValues($keys));
    }

    public static function getImageConfigurations()
    {
        $config = [];

        $config["BRAND"] = self::addToConfig(160, 40, "4:1", 4);
        $config["CATEGORY"] = self::addToConfig(2000, 400, "5:1", 5);
        $config["DISCOUNT_COUPON"] = self::addToConfig(250, 250, "1:1", 1);
        $config["BLOG"] = self::addToConfig(1000, 750, "4:3", 1.33);
        $config["TESTIMONIAL"] = self::addToConfig(100, 100, "1:1", 1);
        $config["ADMIN_PHOTO"] = self::addToConfig(250, 250, "1:1", 1);
        $config["USER_PHOTO"] = self::addToConfig(250, 250, "1:1", 1);
        $config["EMAIL_LOGO_1_1"] = self::addToConfig(100, 100, "1:1", 1);
        $config["EMAIL_LOGO_16_9"] = self::addToConfig(160, 90, "16:9", 1.77);
        $config["FRONTEND_LOGO_1_1"] = self::addToConfig(100, 100, "1:1", 1);
        $config["FRONTEND_LOGO_16_9"] = self::addToConfig(160, 90, "16:9", 1.77);
        $config["ADMIN_LOGO_1_1"] = self::addToConfig(100, 100, "1:1", 1);
        $config["ADMIN_LOGO_16_9"] = self::addToConfig(160, 90, "16:9", 1.77);
        $config["FAVICON"] = self::addToConfig(32, 32, "1:1", 1);
        $config["PRODUCT_3_4"] = self::addToConfig(800, 1067, "3:4", 0.75);
        $config["PRODUCT_3_4_M"] = self::addToConfig(330, 440, "3:4", 0.75);
        $config["PRODUCT_3_4_S"] = self::addToConfig(48, 64, "3:4", 0.75);
        $config["PRODUCT_16_9"] = self::addToConfig(800, 450, "16:9", 1.77);
        $config["PRODUCT_16_9_M"] = self::addToConfig(400, 225, "16:9", 1.77);
        $config["PRODUCT_16_9_S"] = self::addToConfig(48, 27, "16:9", 1.77);
        $config["PRODUCT_1_1"] = self::addToConfig(800, 800, "1:1", 1);
        $config["PRODUCT_1_1_M"] = self::addToConfig(400, 400, "1:1", 1);
        $config["PRODUCT_1_1_S"] = self::addToConfig(48, 48, "1:1", 1);

        /**cms collections */
        $config["CATEGORY2"] = self::addToConfig(250, 250, "1:1", 1);
        $config["CATEGORY3"] = self::addToConfig(250, 250, "1:1", 1);
        $config["CATEGORY4"] = self::addToConfig(800, 800, "1:1", 1);
        $config["BANNER_DESKTOP"] = self::addToConfig(2000, 500, "4:1", 4);
        $config["BANNER_TABLET"] = self::addToConfig(1200, 800, "3:2", 1.5);
        $config["BANNER_MOBILE"] = self::addToConfig(800, 600, "4:3", 1.33);
        $config["PROMOTIONAL_BANNER1"] = self::addToConfig(2000, 1000, "2:1", 2);
        $config["PROMOTIONAL_BANNER2"] = self::addToConfig(800, 600, "4:3", 1.33);
        $config["PROMOTIONAL_BANNER3"] = self::addToConfig(2000, 500, "4:1", 4);
        $config["NEWSLETTER1"] = self::addToConfig(2000, 500, "4:1", 4);
        $config["NEWSLETTER2"] = self::addToConfig(1800, 300, "6:1", 6);
        $config["INSTAGRAM"] = self::addToConfig(400, 400, "1:1", 1);
        $config["TITLEWITHBACKGROUNDIMAGE"] = self::addToConfig(2000, 500, "4:1", 4);
        $config["IMAGEGALLERY"] = self::addToConfig(600, 600, "1:1", "NaN");
        $config["TEXTWITH2IMAGESONLEFT"] = self::addToConfig(400, 533, "3:4", 0.75);
        $config["TEXTWITH2IMAGESONRIGHT"] = self::addToConfig(400, 533, "3:4", 0.75);
        $config["IMAGE"] = self::addToConfig(800, 600, "4:3", "NaN");
        
        return $config;
    }

    private static function addToConfig($width, $height, $aspectRatio, $calculatedRatio)
    {
        return [
            "WIDTH" => $width,
            "HEIGHT" => $height,
            "ASPECTRATIO" => $aspectRatio,
            "CALCULATEDRATIO" => $calculatedRatio,
            "DUMMY" => str_replace(":", "_", $aspectRatio) . "/" . $width . "x" . $height . ".png",
            "SIZE" => $width . "x" . $height
        ];
    }
}
