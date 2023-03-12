<?php

namespace Database\Seeders;

use App\Models\UserSavedProduct;
use Illuminate\Database\Seeder;

class UserSavedProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserSavedProduct::truncate();
        $sql =  base_path('public/dummydata/yokart_user_saved_products.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
