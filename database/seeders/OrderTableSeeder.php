<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::truncate();
        $sql =  base_path('public/dummydata/yokart_orders.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
