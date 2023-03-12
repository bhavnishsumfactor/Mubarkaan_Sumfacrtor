<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialPriceExcludesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_price_excludes', function (Blueprint $table) {
            $table->bigInteger('spe_splprice_id')->index();
            $table->integer('spe_record_id')->index();
            $table->string('spe_subrecord_id', 255)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_price_excludes');
    }
}
