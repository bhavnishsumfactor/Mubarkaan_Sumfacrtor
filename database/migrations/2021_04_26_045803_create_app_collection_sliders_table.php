<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppCollectionSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_collection_sliders', function (Blueprint $table) {
            $table->bigIncrements('acs_id');
            $table->bigInteger('acs_actr_id');
            $table->bigInteger('acs_type');
            $table->index('acs_actr_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_collection_sliders');
    }
}
