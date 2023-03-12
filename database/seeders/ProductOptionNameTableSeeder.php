<?php

namespace Database\Seeders;

use App\Models\ProductOptionName;
use Illuminate\Database\Seeder;

class ProductOptionNameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductOptionName::truncate();
        $sql =  base_path('public/dummydata/yokart_product_option_names.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
