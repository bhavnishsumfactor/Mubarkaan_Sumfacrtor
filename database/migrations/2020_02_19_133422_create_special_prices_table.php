<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_prices', function (Blueprint $table) {
            $table->bigIncrements('splprice_id');
            $table->string('splprice_name', 255);
            $table->integer('splprice_type');
            $table->string('splprice_amount', 50);
            $table->dateTime('splprice_startdate');
            $table->dateTime('splprice_enddate');
            $table->integer('splprice_include');
            $table->boolean('splprice_publish')->default(true);
            $table->integer('splprice_created_by_id');
            $table->integer('splprice_updated_by_id');
            $table->dateTime('splprice_created_at');
            $table->dateTime('splprice_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_prices');
    }
}
