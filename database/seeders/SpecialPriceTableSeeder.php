<?php

namespace Database\Seeders;

use App\Models\SpecialPrice;
use Illuminate\Database\Seeder;

class SpecialPriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SpecialPrice::truncate();
        $sql =  base_path('public/dummydata/yokart_special_prices.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
