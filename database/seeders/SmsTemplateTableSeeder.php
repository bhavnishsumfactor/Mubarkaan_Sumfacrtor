<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;

class SmsTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SmsTemplate::truncate();
        $sql =  base_path('public/dummydata/yokart_sms_templates.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
