<?php

namespace Database\Seeders;

use App\Models\ShippingBoxPackage;
use App\Models\ShippingProfile;
use App\Models\ShippingProfileZone;
use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingTableSeeder extends Seeder
{
    public $boxPackages = [
        [
          'sbpkg_id' => 1,
          'sbpkg_name' => "Standard package",
          'sbpkg_length' => 7.50,
          'sbpkg_width' => 14,
          'sbpkg_heigth' => 0.62,
          'sbpkg_dimension_type' => 0,
          'sbpkg_default' => 1
        ]
      ];
    public $profiles = [
        [
          'sprofile_id' => 1,
          'sprofile_name' => "General",
          'sprofile_default' => 1,
        ],
        [
          'sprofile_id' => 2,
          'sprofile_name' => "Fragile",
          'sprofile_default' => 0,
          
        ]
      ];
    
    public function run()
    {
        ShippingBoxPackage::truncate();
        ShippingProfile::truncate();
        ShippingBoxPackage::insert($this->boxPackages);
        ShippingProfile::insert($this->profiles);

    }
}
