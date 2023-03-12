<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_categories', function (Blueprint $table) {
            $table->bigIncrements('bpcat_id');
            $table->string('bpcat_name')->unique();
            $table->integer('bpcat_parent');
            $table->boolean('bpcat_publish')->default(0);
            $table->integer('bpcat_display_order');
            $table->integer('bpcat_created_by_id');
            $table->integer('bpcat_updated_by_id');
            $table->dateTime('bpcat_created_at');
            $table->dateTime('bpcat_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_post_categories');
    }
}
