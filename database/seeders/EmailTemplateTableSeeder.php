<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailTemplate::truncate();
        $sql =  base_path('public/dummydata/yokart_email_templates.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
