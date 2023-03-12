<?php

namespace Database\Seeders;

use App\Models\ShippingToLocation;
use Illuminate\Database\Seeder;

class ShippingToLocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingToLocation::truncate();
        $sql =  base_path('public/dummydata/yokart_shipping_to_locations.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
