<?php

namespace Database\Seeders;
use App\Models\Page;
use Illuminate\Database\Seeder;

class BlankPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::truncate();
        $sql =  base_path('public/dummydata/yokart_blank_pages.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
