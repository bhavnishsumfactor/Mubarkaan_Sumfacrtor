<?php

namespace Database\Seeders;

use App\Models\OptionToVarient;
use Illuminate\Database\Seeder;

class OptionToVarientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OptionToVarient::truncate();
        $sql =  base_path('public/dummydata/yokart_option_to_varients.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
