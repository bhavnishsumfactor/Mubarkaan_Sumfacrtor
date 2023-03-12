<?php

namespace Database\Seeders;

use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserAddress::truncate();
        $sql =  base_path('public/dummydata/yokart_user_addresses.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
