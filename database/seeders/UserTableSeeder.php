<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $sql =  base_path('public/dummydata/yokart_users.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
