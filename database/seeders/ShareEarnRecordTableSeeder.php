<?php

namespace Database\Seeders;

use App\Models\ShareEarnRecord;
use Illuminate\Database\Seeder;

class ShareEarnRecordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShareEarnRecord::truncate();
        $sql =  base_path('public/dummydata/yokart_share_earn_records.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
