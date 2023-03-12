<?php

namespace Database\Seeders;

use App\Models\Admin\NotificationToAdmin;
use Illuminate\Database\Seeder;

class NotificationToAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationToAdmin::truncate();
        $sql =  base_path('public/dummydata/yokart_notification_to_admins.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
