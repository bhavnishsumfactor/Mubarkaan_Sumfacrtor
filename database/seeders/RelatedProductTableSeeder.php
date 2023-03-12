<?php

namespace Database\Seeders;

use App\Models\RelatedProduct;
use Illuminate\Database\Seeder;

class RelatedProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RelatedProduct::truncate();
        $sql =  base_path('public/dummydata/yokart_related_products.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
