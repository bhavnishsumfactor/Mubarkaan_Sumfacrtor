<?php

namespace Database\Seeders;

use App\Models\ShippingProfileZone;
use Illuminate\Database\Seeder;

class ShippingProfileZoneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingProfileZone::truncate();
        $sql =  base_path('public/dummydata/yokart_shipping_profile_zones.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
