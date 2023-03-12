<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingBoxPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_box_packages', function (Blueprint $table) {
            $table->increments('sbpkg_id');
            $table->string('sbpkg_name');
            $table->string('sbpkg_length');
            $table->string('sbpkg_width');
            $table->string('sbpkg_heigth');
            $table->string('sbpkg_dimension_type');
            $table->boolean('sbpkg_default')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_box_packages');
    }
}
