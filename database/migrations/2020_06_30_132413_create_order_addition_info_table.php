<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAdditionInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_addition_info', function (Blueprint $table) {
            $table->bigInteger('oainfo_order_id');
            $table->string('oainfo_received_confirmation')->nullable();
            $table->string('oainfo_courier_name')->nullable();
            $table->string('oainfo_cat_tax_code')->nullable();
            $table->string('oainfo_tracking_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_addition_info');
    }
}
