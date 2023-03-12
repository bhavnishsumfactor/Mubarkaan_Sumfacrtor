<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->integer('order_user_id');
            $table->integer('order_payment_status');
            $table->integer('order_status');
            $table->integer('order_shipping_status')->nullable();
            $table->decimal('order_net_amount', 10, 2);
            $table->decimal('order_amount_charged', 10, 2);
            $table->decimal('order_tax_charged', 10, 2);
            $table->string('order_discount_coupon_code')->nullable();
            $table->boolean('order_discount_type')->nullable();
            $table->decimal('order_discount_amount', 10, 2)->nullable();
            $table->string('order_currency_code')->nullable();
            $table->string('order_currency_symbol')->nullable();
            $table->string('order_currency_value')->nullable();
            $table->string('order_payment_method')->nullable();
            $table->integer('order_reward_points')->nullable();
            $table->decimal('order_reward_amount', 10, 2)->nullable();
            $table->decimal('order_shipping_value', 10, 2)->nullable();
            $table->decimal('order_gift_amount', 10, 2)->nullable();
            $table->text('order_shipping_methods')->nullable();
            $table->boolean('order_shipping_type')->default(true);
            $table->longText('order_cart_data')->nullable();
            $table->text('order_notes')->nullable();
            $table->dateTime('order_date_added');
            $table->dateTime('order_date_updated');
        });
        $prefix = \DB::getTablePrefix();
        \DB::update("ALTER TABLE ".$prefix."orders AUTO_INCREMENT = 10000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
