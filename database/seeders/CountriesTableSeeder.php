<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::truncate();
        $sql =  base_path('public/dummydata/yokart_countries.sql');
        \DB::unprepared(file_get_contents($sql));

        
    }
}
