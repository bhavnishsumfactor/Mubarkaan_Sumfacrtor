<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingProfileZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_profile_zones', function (Blueprint $table) {
            $table->bigIncrements('spzone_id');
            $table->integer('spzone_sprofile_id');
            $table->string('spzone_name');
            $table->boolean('spzone_shipping_type')->default(false);
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_profile_zones');
    }
}
