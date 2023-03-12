<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialPriceIncludesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_price_includes', function (Blueprint $table) {
            $table->bigInteger('spi_splprice_id')->index();
            $table->integer('spi_record_id')->index();
            $table->string('spi_subrecord_id', 255)->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_price_includes');
    }
}
