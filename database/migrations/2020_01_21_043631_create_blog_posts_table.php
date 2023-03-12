<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->bigIncrements('post_id');
            $table->string('post_title');
            $table->boolean('post_publish')->default(0);
            $table->string('post_author_name');
            $table->boolean('post_comment_opened')->default(0);
            $table->boolean('post_featured')->default(0);
            $table->integer('post_view_count')->default(0);
            $table->datetime('post_published_on')->nullable();
            $table->integer('post_created_by_id');
            $table->integer('post_updated_by_id');
            $table->datetime('post_created_at');
            $table->datetime('post_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
