<?php

namespace Database\Seeders;

use App\Models\OrderAddress;
use Illuminate\Database\Seeder;

class OrderAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderAddress::truncate();
        $sql =  base_path('public/dummydata/yokart_order_addresses.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
