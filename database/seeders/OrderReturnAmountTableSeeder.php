<?php

namespace Database\Seeders;

use App\Models\OrderReturnAmount;
use Illuminate\Database\Seeder;

class OrderReturnAmountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderReturnAmount::truncate();
        $sql =  base_path('public/dummydata/yokart_order_return_amounts.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
