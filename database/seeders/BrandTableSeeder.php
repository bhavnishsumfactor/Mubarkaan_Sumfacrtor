<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\UrlRewrite;
use Illuminate\Database\Seeder;
use App\Models\AttachedFile;
use DB;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Brand::truncate();
        $sql =  base_path('public/dummydata/yokart_brands.sql');
        DB::unprepared(file_get_contents($sql));
      
        /*  ini_set('memory_limit', '1024M');

        Brand::factory()->count(200)->create()->each(function ($brand) {
            UrlRewrite::saveUrl('brands', $brand->brand_id);
            $record = AttachedFile::where('afile_type', AttachedFile::SECTIONS['brandLogo'])->inRandomOrder()->first();
            $duplicate = $record->replicate();
            $duplicate->afile_record_id = $brand->brand_id;
            $duplicate->save();
        });*/
    }
}
