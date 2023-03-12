<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_to_categories', function (Blueprint $table) {
            $table->bigInteger('ptc_bpcat_id');
            $table->bigInteger('ptc_post_id');
            $table->primary(['ptc_bpcat_id', 'ptc_post_id']); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_post_to_categories');
    }
}
