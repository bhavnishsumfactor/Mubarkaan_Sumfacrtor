<?php

namespace Database\Seeders;

use App\Models\BlogPostToCategory;
use Illuminate\Database\Seeder;
use DB;

class BlogPostToCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogPostToCategory::truncate();
        $sql =  base_path('public/dummydata/yokart_blog_post_to_categories.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
