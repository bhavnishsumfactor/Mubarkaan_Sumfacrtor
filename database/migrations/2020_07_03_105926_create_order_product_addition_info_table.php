<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductAdditionInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_addition_info', function (Blueprint $table) {
            $table->bigInteger('opainfo_op_id');
            $table->integer('opainfo_max_download_limit')->nullable();
            $table->string('opainfo_cat_tax_code')->nullable();
            $table->integer('opainfo_downloads')->nullable();
            $table->boolean('opainfo_upload_additional_files')->default(false);
            $table->string('opainfo_discount_amount')->nullable();
            $table->string('opainfo_gift_amount')->nullable();
            $table->integer('opainfo_tgtype_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product_addition_info');
    }
}
