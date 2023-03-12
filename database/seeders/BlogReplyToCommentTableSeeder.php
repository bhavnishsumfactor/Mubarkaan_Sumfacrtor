<?php

namespace Database\Seeders;

use App\Models\BlogReplyToComment;
use Illuminate\Database\Seeder;
use DB;

class BlogReplyToCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogReplyToComment::truncate();
        $sql =  base_path('public/dummydata/yokart_blog_reply_to_comments.sql');
        DB::unprepared(file_get_contents($sql));
        
    }
}
