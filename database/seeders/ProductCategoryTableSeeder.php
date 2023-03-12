<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\UrlRewrite;
use App\Models\AttachedFile;

use Illuminate\Database\Seeder;

class ProductCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCategory::truncate();
        $sql =  base_path('public/dummydata/yokart_product_categories.sql');
        \DB::unprepared(file_get_contents($sql));


/*
        ProductCategory::factory()->count(500)->create()->each(function ($category) {
            UrlRewrite::saveUrl('categories', $category->cat_id);
            $record = AttachedFile::where('afile_type', AttachedFile::SECTIONS['productCategoryBanner'])->inRandomOrder()->first();
            $duplicate = $record->replicate();
            $duplicate->afile_record_id = $category->cat_id;
            $duplicate->save();
        });*/
  
    }
}
