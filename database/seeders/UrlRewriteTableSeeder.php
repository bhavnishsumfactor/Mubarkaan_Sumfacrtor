<?php

namespace Database\Seeders;

use App\Models\UrlRewrite;
use Illuminate\Database\Seeder;

class UrlRewriteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UrlRewrite::truncate();
        $sql =  base_path('public/dummydata/yokart_url_rewrites.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
