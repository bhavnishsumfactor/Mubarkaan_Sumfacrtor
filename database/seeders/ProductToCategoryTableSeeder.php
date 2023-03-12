<?php

namespace Database\Seeders;

use App\Models\ProductToCategory;
use Illuminate\Database\Seeder;

class ProductToCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductToCategory::truncate();
        $sql =  base_path('public/dummydata/yokart_product_to_categories.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
