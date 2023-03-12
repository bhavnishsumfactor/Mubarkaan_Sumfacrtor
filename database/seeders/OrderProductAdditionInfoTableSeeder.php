<?php

namespace Database\Seeders;

use App\Models\OrderProductAdditionInfo;
use Illuminate\Database\Seeder;

class OrderProductAdditionInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderProductAdditionInfo::truncate();
        $sql =  base_path('public/dummydata/yokart_order_product_addition_info.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
