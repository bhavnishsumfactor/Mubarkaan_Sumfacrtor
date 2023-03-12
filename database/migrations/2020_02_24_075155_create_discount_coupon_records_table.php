<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCouponRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_coupon_records', function (Blueprint $table) {
            $table->integer('dcr_type');
            $table->bigInteger('dcr_discountcpn_id');
            $table->bigInteger('dcr_record_id');
            $table->string('dcr_subrecord_id', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_coupon_records');
    }
}
