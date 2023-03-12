<?php

namespace Database\Seeders;

use App\Models\OrderProductTaxLog;
use Illuminate\Database\Seeder;

class OrderProductTaxLogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderProductTaxLog::truncate();
        $sql =  base_path('public/dummydata/yokart_order_product_tax_logs.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
