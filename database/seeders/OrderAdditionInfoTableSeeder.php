<?php

namespace Database\Seeders;

use App\Models\OrderAdditionInfo;
use Illuminate\Database\Seeder;

class OrderAdditionInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderAdditionInfo::truncate();
        $sql =  base_path('public/dummydata/yokart_order_addition_info.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
