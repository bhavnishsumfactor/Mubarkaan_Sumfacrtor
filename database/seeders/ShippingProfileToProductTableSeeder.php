<?php

namespace Database\Seeders;

use App\Models\ShippingProfileToProduct;
use Illuminate\Database\Seeder;

class ShippingProfileToProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingProfileToProduct::truncate();
        $sql =  base_path('public/dummydata/yokart_shipping_profile_to_products.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
