<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ContactPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql =  base_path('public/dummydata/contactuspage/yokart_contactus.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
