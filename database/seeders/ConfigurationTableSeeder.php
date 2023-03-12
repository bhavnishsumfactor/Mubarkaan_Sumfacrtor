<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;
use DB;

class ConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuration::truncate();
        $sql =  base_path('public/dummydata/yokart_configurations.sql');
        DB::unprepared(file_get_contents($sql));
        $dateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 hours'));


        $exists = Configuration::where('conf_name', "CONF_RESTORE_SCHEDULE_TIME")->first();
        if (empty($exists)) {
            Configuration::insert([
                'conf_name' => "CONF_RESTORE_SCHEDULE_TIME",
                'conf_val' => $dateTime
            ]);
        } else {
            Configuration::where('conf_name', "CONF_RESTORE_SCHEDULE_TIME")->update(['conf_val' => $dateTime]);
        }
        \Cache::forget('configuration');
    }
}
