<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AboutPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql =  base_path('public/dummydata/aboutpage/yokart_about.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
