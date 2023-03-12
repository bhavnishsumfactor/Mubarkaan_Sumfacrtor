<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->bigIncrements('discountcpn_id');
            $table->string('discountcpn_code', 50);
            $table->boolean('discountcpn_in_percent')->default(false);
            $table->integer('discountcpn_type');
            $table->integer('discountcpn_total_uses');
            $table->integer('discountcpn_uses_per_user');
            $table->string('discountcpn_maxamt_limit', 50);
            $table->string('discountcpn_minorderamt', 50);
            $table->string('discountcpn_amount', 50);
            $table->boolean('discountcpn_operator')->default(true);
            $table->boolean('discountcpn_for_guest')->default(true);
            $table->dateTime('discountcpn_startdate');
            $table->dateTime('discountcpn_enddate');
            $table->boolean('discountcpn_publish')->default(true);
            $table->integer('discountcpn_created_by_id');
            $table->integer('discountcpn_updated_by_id');
            $table->dateTime('discountcpn_created_at');
            $table->dateTime('discountcpn_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_coupons');
    }
}
