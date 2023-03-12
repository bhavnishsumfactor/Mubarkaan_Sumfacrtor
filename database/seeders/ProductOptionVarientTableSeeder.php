<?php

namespace Database\Seeders;

use App\Models\ProductOptionVarient;
use Illuminate\Database\Seeder;

class ProductOptionVarientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductOptionVarient::truncate();
        $sql =  base_path('public/dummydata/yokart_product_option_varients.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
