<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingRateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingRate::truncate();
        $sql =  base_path('public/dummydata/yokart_shipping_rates.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
