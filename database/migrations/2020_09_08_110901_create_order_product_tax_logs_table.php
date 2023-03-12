<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductTaxLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product_tax_logs', function (Blueprint $table) {
            $table->bigInteger('optl_order_id');
            $table->bigInteger('optl_op_id');
            $table->string('optl_key');
            $table->string('optl_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product_tax_logs');
    }
}
