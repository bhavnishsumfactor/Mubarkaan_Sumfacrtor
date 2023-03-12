<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReturnAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_return_amounts', function (Blueprint $table) {
            $table->bigIncrements('oramount_id');
            $table->bigInteger('oramount_orrequest_id');
            $table->bigInteger('oramount_op_id');
            $table->string('oramount_tax');
            $table->string('oramount_shipping');
            $table->string('oramount_discount');
            $table->string('oramount_giftwrap_price');
            $table->string('oramount_reward_price');
            $table->boolean('oramount_payment_status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_return_amounts');
    }
}
