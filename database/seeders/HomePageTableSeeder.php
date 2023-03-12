<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class HomePageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql =  base_path('public/dummydata/homepage/yokart_home.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
