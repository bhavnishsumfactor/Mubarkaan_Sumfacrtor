<?php

namespace Database\Seeders;

use App\Models\Admin\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(array $admin = [])
    {
        $adminName = $admin["admin_name"] ?? "admin";
        $adminEmail = $admin["admin_email"] ?? "admin@dummyid.com";
        $adminPassword = $admin["admin_password"] ?? "Admin@123";
        Admin::truncate();
        $admin = [[
            'admin_id' => 1,
            'admin_name' => $adminName,
            'admin_username' => $adminName,
            'admin_email' => $adminEmail,
            'admin_password' => Hash::make($adminPassword),
            'admin_role_id' => null,
            'admin_publish' =>  1,
            'admin_created_at' =>  now(),
            'admin_updated_at' =>  now(),
        ]];
        Admin::insert($admin);
    }
}
