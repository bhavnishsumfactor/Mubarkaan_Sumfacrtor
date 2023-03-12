<?php

namespace Database\Seeders;

use App\Models\BlogPostContent;
use Illuminate\Database\Seeder;
use DB;

class BlogPostContentTableSeeder extends Seeder
{ 
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogPostContent::truncate();
        $sql =  base_path('public/dummydata/yokart_blog_post_contents.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
