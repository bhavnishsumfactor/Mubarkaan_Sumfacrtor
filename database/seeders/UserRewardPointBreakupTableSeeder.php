<?php

namespace Database\Seeders;

use App\Models\UserRewardPointBreakup;
use Illuminate\Database\Seeder;

class UserRewardPointBreakupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRewardPointBreakup::truncate();
        $sql =  base_path('public/dummydata/yokart_user_reward_point_breakup.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
