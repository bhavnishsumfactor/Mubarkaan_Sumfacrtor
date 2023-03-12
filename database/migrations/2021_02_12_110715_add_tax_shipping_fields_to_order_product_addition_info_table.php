<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxShippingFieldsToOrderProductAdditionInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_product_addition_info', function (Blueprint $table) {
            $table->string('opainfo_reward_rate', 20)->nullable();
            $table->string('opainfo_shipping_amount', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_product_addition_info', function (Blueprint $table) {
            $table->dropColumn('opainfo_reward_rate');
            $table->dropColumn('opainfo_shipping_amount');
        });
    }
}
