<?php

namespace Database\Seeders;

use App\Models\ProductOption;
use Illuminate\Database\Seeder;

class ProductOptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductOption::truncate();
        $sql =  base_path('public/dummydata/yokart_product_options.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
