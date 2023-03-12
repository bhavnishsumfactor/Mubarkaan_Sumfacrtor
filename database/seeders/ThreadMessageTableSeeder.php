<?php

namespace Database\Seeders;

use App\Models\ThreadMessage;
use Illuminate\Database\Seeder;

class ThreadMessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ThreadMessage::truncate();
        $sql =  base_path('public/dummydata/yokart_thread_messages.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
