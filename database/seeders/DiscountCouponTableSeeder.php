<?php

namespace Database\Seeders;

use App\Models\DiscountCoupon;
use Illuminate\Database\Seeder;

class DiscountCouponTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiscountCoupon::truncate();
        $sql =  base_path('public/dummydata/yokart_discount_coupons.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
