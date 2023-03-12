<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_contents', function (Blueprint $table) {
            $table->biginteger('pc_prod_id')->index();
            $table->string('pc_sku')->default('');
            $table->integer('pc_threshold_stock_level')->default(0);
            $table->integer('pc_substract_stock')->default(0);
            $table->integer('pc_gift_wrap_available')->default(0);
            $table->string('pc_weight')->nullable();
            $table->string('pc_weight_unit')->nullable();
            $table->integer('pc_warranty_age')->nullable();
            $table->integer('pc_return_age')->nullable();
            $table->string('pc_isbn')->nullable();
            $table->string('pc_hsn')->nullable();
            $table->string('pc_sac')->nullable();
            $table->string('pc_upc')->nullable();
            $table->string('pc_file_title')->nullable();
            $table->string('pc_video_link')->nullable();
            $table->integer('pc_max_download_times')->nullable();
            $table->integer('pc_download_validity_in_days')->nullable();
            $table->integer('pc_cancellation_age')->nullable();
            $table->boolean('pc_upload_additional_files')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_contents');
    }
}
