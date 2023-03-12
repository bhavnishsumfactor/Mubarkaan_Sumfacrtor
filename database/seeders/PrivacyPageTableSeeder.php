<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class PrivacyPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql =  base_path('public/dummydata/privacypage/yokart_privacy.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
