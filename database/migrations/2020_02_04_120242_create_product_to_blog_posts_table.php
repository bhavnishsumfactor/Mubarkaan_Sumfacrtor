<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductToBlogPostsTable extends Migration
{
    /**
        * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_to_blog_posts', function (Blueprint $table) {
            $table->bigInteger('ptbp_post_id');
            $table->bigInteger('ptbp_prod_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_to_blog_posts');
    }
}
