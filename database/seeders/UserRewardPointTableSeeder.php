<?php

namespace Database\Seeders;

use App\Models\UserRewardPoint;
use Illuminate\Database\Seeder;

class UserRewardPointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRewardPoint::truncate();
        $sql =  base_path('public/dummydata/yokart_user_reward_points.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
