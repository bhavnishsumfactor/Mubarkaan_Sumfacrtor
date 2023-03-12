<?php

namespace Database\Seeders;

use App\Models\SpecialPriceInclude;
use Illuminate\Database\Seeder;

class SpecialPriceIncludeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SpecialPriceInclude::truncate();
        $sql =  base_path('public/dummydata/yokart_special_price_includes.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
