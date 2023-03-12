<?php

namespace Database\Seeders;

use App\Models\ProductContent;
use Illuminate\Database\Seeder;

class ProductContentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductContent::truncate();
        $sql =  base_path('public/dummydata/yokart_product_contents.sql');
        \DB::unprepared(file_get_contents($sql));

    }
}
