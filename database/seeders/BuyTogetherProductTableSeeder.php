<?php

namespace Database\Seeders;

use App\Models\BuyTogetherProduct;
use Illuminate\Database\Seeder;
use DB;

class BuyTogetherProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BuyTogetherProduct::truncate();
        $sql =  base_path('public/dummydata/yokart_buy_together_products.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
