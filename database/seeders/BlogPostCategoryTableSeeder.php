<?php

namespace Database\Seeders;

use App\Models\BlogPostCategory;
use Illuminate\Database\Seeder;
use DB;

class BlogPostCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogPostCategory::truncate();
        $sql =  base_path('public/dummydata/yokart_blog_post_categories.sql');
        DB::unprepared(file_get_contents($sql));
 
    }
}
