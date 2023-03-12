<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_histories', function (Blueprint $table) {
            $table->bigIncrements('couponhistory_id');
            $table->bigInteger('couponhistory_coupon_id');
            $table->bigInteger('couponhistory_order_id');
            $table->bigInteger('couponhistory_user_id');
            $table->decimal('couponhistory_amount', 10, 2)->nullable();
            $table->dateTime('couponhistory_added_on');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_histories');
    }
}
