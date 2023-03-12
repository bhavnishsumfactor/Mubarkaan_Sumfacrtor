<?php

namespace Database\Seeders;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use DB;

class TestimonialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Testimonial::truncate();
        $sql =  base_path('public/dummydata/yokart_testimonials.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
