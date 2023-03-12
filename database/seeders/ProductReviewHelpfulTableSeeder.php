<?php

namespace Database\Seeders;

use App\Models\ProductReviewHelpful;
use Illuminate\Database\Seeder;

class ProductReviewHelpfulTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductReviewHelpful::truncate();
        $sql =  base_path('public/dummydata/yokart_product_review_helpful.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
