<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetaTag;
use DB;

class MetaTagTableSeeder extends Seeder
{

    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    

    public function run()
    {
        MetaTag::truncate();
        $sql =  base_path('public/dummydata/yokart_meta_tags.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
