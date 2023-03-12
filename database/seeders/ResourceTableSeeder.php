<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;
use DB;

class ResourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Resource::truncate();
        $sql =  base_path('public/dummydata/yokart_resources.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
