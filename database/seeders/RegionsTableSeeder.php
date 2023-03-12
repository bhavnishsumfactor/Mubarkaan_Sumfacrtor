<?php

namespace Database\Seeders;

use App\Models\Region;

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::truncate();
        Region::insert(['region_name' => 'Asia']);
        Region::insert(['region_name' => 'Africa']);
        Region::insert(['region_name' => 'Americas']);
        Region::insert(['region_name' => 'Europe']);
        Region::insert(['region_name' => 'Oceania']);
    }
}
