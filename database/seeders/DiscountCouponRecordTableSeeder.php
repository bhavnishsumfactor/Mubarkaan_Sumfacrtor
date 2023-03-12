<?php

namespace Database\Seeders;

use App\Models\DiscountCouponRecord;
use Illuminate\Database\Seeder;

class DiscountCouponRecordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiscountCouponRecord::truncate();
        $sql =  base_path('public/dummydata/yokart_discount_coupon_records.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
