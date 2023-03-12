<?php

namespace Database\Seeders;

use App\Models\OrderProduct;
use Illuminate\Database\Seeder;

class OrderProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderProduct::truncate();
        $sql =  base_path('public/dummydata/yokart_order_products.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
