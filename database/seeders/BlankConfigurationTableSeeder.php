<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BlankConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($data = "", $dummyData = 0)
    {
        Configuration::truncate();
        $sql =  base_path('public/dummydata/yokart_blank_configurations.sql');
        \DB::unprepared(file_get_contents($sql));

        if (!empty($data)) {
            $data = json_decode($data, true);
            $data['INSTALLATION_WITH_DATA'] = $dummyData;
            $data['INSTALLATION_DATE'] = Carbon::now()->toDateString();
            Configuration::bulkUpdate($data);
        }

        \Cache::forget('configuration');
    }
}
