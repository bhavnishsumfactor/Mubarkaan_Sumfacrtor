<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Collection;
use App\Models\CollectionMeta;
use App\Models\NavigationMenu;
use App\Models\Slide;
use DB;
use Illuminate\Database\Seeder;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Collection::truncate();
        CollectionMeta::truncate();
        Slide::truncate();
        NavigationMenu::truncate();
        Page::truncate();
        $sql =  base_path('public/dummydata/yokart_other_pages.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
