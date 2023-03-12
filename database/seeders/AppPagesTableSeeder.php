<?php

namespace Database\Seeders;

use App\Models\AppPage;
use Illuminate\Database\Seeder;
use DB;

class AppPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppPage::truncate();
        $sql =  base_path('public/dummydata/yokart_app_pages.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
