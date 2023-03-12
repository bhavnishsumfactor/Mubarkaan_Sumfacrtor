<?php

namespace Database\Seeders;

use App\Models\ProductReview;
use Illuminate\Database\Seeder;

class ProductReviewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductReview::truncate();
        $sql =  base_path('public/dummydata/yokart_product_reviews.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
