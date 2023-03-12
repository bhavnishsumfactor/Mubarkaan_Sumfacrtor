<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->bigIncrements('preview_id');
            $table->bigInteger('preview_order_id');
            $table->bigInteger('preview_user_id');
            $table->bigInteger('preview_prod_id');
            $table->string('preview_rating', 50);
            $table->string('preview_title', 255);
            $table->text('preview_description')->nullable();
            $table->boolean('preview_publish')->default(true);
            $table->integer('preview_status')->default(1);
            $table->dateTime('preview_approved_at')->nullable();
            $table->dateTime('preview_created_at');
            $table->dateTime('preview_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_reviews');
    }
}
