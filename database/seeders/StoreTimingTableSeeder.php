<?php

namespace Database\Seeders;

use App\Models\StoreTiming;
use Illuminate\Database\Seeder;

class StoreTimingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreTiming::truncate();
        $sql =  base_path('public/dummydata/yokart_store_timings.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
