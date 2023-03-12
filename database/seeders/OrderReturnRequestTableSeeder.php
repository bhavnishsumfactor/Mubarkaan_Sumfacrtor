<?php

namespace Database\Seeders;

use App\Models\OrderReturnRequest;
use Illuminate\Database\Seeder;

class OrderReturnRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderReturnRequest::truncate();
        $sql =  base_path('public/dummydata/yokart_order_return_requests.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
