<?php

namespace Database\Seeders;

use App\Models\UserCard;
use Illuminate\Database\Seeder;

class UserCardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserCard::truncate();
        $sql =  base_path('public/dummydata/yokart_user_cards.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
