<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStockHoldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock_holds', function (Blueprint $table) {
            $table->bigIncrements('pshold_id');
            $table->integer('pshold_prod_id');
            $table->string('pshold_pov_code')->nullable();
            $table->string('pshold_session_id');
            $table->integer('pshold_prod_stock');
            $table->datetime('pshold_added_on');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_stock_holds');
    }
}
