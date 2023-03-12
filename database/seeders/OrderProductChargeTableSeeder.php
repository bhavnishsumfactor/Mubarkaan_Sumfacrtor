<?php

namespace Database\Seeders;

use App\Models\OrderProductCharge;
use Illuminate\Database\Seeder;

class OrderProductChargeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderProductCharge::truncate();
        $sql =  base_path('public/dummydata/yokart_order_product_charges.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
