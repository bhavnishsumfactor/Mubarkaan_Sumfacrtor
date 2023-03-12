<?php

namespace Database\Seeders;

use App\Models\AppCollection;
use Illuminate\Database\Seeder;
use DB;

class AppCollectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppCollection::truncate();
        $sql =  base_path('public/dummydata/yokart_app_collections.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
