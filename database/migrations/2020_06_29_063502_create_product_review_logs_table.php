<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_review_logs', function (Blueprint $table) {
            $table->bigIncrements('prl_id');
            $table->bigInteger('prl_preview_id')->unique();
            $table->string('prl_preview_rating', 50);
            $table->string('prl_preview_title', 255);
            $table->text('prl_preview_description')->nullable();
            $table->boolean('prl_preview_status')->default(false);
            $table->dateTime('prl_preview_created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_review_logs');
    }
}
