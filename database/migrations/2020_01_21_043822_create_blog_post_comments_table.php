<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_comments', function (Blueprint $table) {
            $table->bigIncrements('bpcomment_id');
            $table->integer('bpcomment_user_id');
            $table->string('bpcomment_author_name');
            $table->string('bpcomment_author_email');
            $table->longText('bpcomment_content');
            $table->boolean('bpcomment_approved')->default(0);
            $table->datetime('bpcomment_added_on');
            $table->string('bpcomment_user_ip');
            $table->text('bpcomment_user_agent'); 
            $table->integer('bpcomment_created_by_id');
            $table->integer('bpcomment_updated_by_id');
            $table->dateTime('bpcomment_created_at');
            $table->dateTime('bpcomment_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_post_comments');
    }
}
