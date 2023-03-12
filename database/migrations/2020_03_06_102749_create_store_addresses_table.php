<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_addresses', function (Blueprint $table) {
            $table->bigIncrements('saddr_id');
            $table->string('saddr_name');
            $table->integer('saddr_country_id');
            $table->integer('saddr_state_id');
            $table->string('saddr_city');
            $table->text('saddr_address');
            $table->string('saddr_phone');
            $table->integer('saddr_phone_country_id');
            $table->string('saddr_postal_code');
            $table->tinyInteger('saddr_shop_open_status')->default('0');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_addresses');
    }
}
