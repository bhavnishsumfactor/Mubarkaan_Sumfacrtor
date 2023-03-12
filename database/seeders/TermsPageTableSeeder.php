<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class TermsPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql =  base_path('public/dummydata/termspage/yokart_terms.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
