<?php

namespace Database\Seeders;

use App\Models\Thread;
use Illuminate\Database\Seeder;

class ThreadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Thread::truncate();
        $sql =  base_path('public/dummydata/yokart_threads.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
