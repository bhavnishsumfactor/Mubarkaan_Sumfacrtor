<?php

namespace Database\Seeders;

use App\Models\Reason;
use Illuminate\Database\Seeder;

class ReasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Reason::truncate();
        $sql =  base_path('public/dummydata/yokart_reasons.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
