<?php

namespace Database\Seeders;
use App\Models\State;
use Illuminate\Database\Seeder;
use DB;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::truncate();
        $sql =  base_path('public/dummydata/yokart_states.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
