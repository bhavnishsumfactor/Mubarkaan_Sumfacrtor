<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->bigIncrements('addr_id');
            $table->bigInteger('addr_user_id');
            $table->string('addr_title');
            $table->string('addr_apartment');
            $table->string('addr_email');
            $table->string('addr_first_name');
            $table->string('addr_last_name');
            $table->text('addr_address1');
            $table->text('addr_address2')->nullable();
            $table->string('addr_city');
            $table->integer('addr_country_id');
            $table->integer('addr_state_id');
            $table->integer('addr_phone_country_id');
            $table->string('addr_postal_code');
            $table->string('addr_phone');
            $table->boolean('addr_billing_default')->default(0);
            $table->boolean('addr_shipping_default')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
}
