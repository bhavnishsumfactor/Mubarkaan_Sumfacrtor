<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppCollectionToRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_collection_to_records', function (Blueprint $table) {
            $table->bigIncrements('actr_id');
            $table->bigInteger('actr_ac_id');
            $table->integer('actr_type');
            $table->integer('actr_slide_duration');
            $table->bigInteger('actr_display_order');
            $table->dateTime('actr_updated_on');
            $table->index('actr_ac_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_collection_to_records');
    }
}
