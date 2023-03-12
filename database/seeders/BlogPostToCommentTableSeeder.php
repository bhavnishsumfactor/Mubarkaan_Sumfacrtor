<?php

namespace Database\Seeders;

use App\Models\BlogPostToComment;
use Illuminate\Database\Seeder;
use DB;

class BlogPostToCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogPostToComment::truncate();
        $sql =  base_path('public/dummydata/yokart_blog_post_to_comments.sql');
        DB::unprepared(file_get_contents($sql));

    }
}
