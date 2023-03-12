<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use DB;

class BlogPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogPost::truncate();
        $sql =  base_path('public/dummydata/yokart_blog_posts.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
