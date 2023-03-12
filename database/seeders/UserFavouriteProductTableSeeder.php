<?php

namespace Database\Seeders;

use App\Models\UserFavouriteProduct;
use Illuminate\Database\Seeder;

class UserFavouriteProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserFavouriteProduct::truncate();
        $sql =  base_path('public/dummydata/yokart_user_favourite_products.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
