<?php

namespace Database\Seeders;

use App\Models\BlogPostComment;
use Illuminate\Database\Seeder;
use DB;

class BlogPostCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogPostComment::truncate();
        $sql =  base_path('public/dummydata/yokart_blog_post_comments.sql');
        DB::unprepared(file_get_contents($sql));
        
    }
}
