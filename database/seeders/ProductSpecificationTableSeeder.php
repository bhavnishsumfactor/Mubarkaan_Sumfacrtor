<?php

namespace Database\Seeders;

use App\Models\ProductSpecification;
use Illuminate\Database\Seeder;

class ProductSpecificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductSpecification::truncate();
        $sql =  base_path('public/dummydata/yokart_product_specifications.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
