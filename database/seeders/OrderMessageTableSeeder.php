<?php

namespace Database\Seeders;

use App\Models\OrderMessage;
use Illuminate\Database\Seeder;

class OrderMessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderMessage::truncate();
        $sql =  base_path('public/dummydata/yokart_order_messages.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
