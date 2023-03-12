<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationTemplate::truncate();
        $sql =  base_path('public/dummydata/yokart_notification_templates.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
