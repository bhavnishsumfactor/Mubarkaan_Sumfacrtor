<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_addresses', function (Blueprint $table) {
            $table->bigInteger('oaddr_order_id');
            $table->string('oaddr_name');
            $table->string('oaddr_email');
            $table->boolean('oaddr_type');
            $table->text('oaddr_address1');
            $table->text('oaddr_address2')->nullable();
            $table->string('oaddr_city');
            $table->string('oaddr_state');
            $table->string('oaddr_country');
            $table->string('oaddr_country_code');
            $table->string('oaddr_postal_code');
            $table->integer('oaddr_phone_country_id')->nullable();
            $table->string('oaddr_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_addresses');
    }
}
