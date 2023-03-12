<?php

namespace Database\Seeders;

use App\Models\AppNotificationTemplate;
use Illuminate\Database\Seeder;
use DB;
class AppNotificationTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppNotificationTemplate::truncate();
        $sql =  base_path('public/dummydata/yokart_app_notification_templates.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
