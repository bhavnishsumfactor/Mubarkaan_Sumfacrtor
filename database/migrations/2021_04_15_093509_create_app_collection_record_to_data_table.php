<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppCollectionRecordToDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_collection_record_to_data', function (Blueprint $table) {
            $table->bigIncrements('acrd_id');
            $table->bigInteger('acrd_actr_id');
            $table->bigInteger('acrd_record_id');
            $table->bigInteger('acrd_subrecord_id');
            $table->bigInteger('acrd_display_order');
            $table->index('acrd_actr_id');
            $table->index('acrd_record_id');
            $table->index('acrd_subrecord_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_collection_record_to_data');
    }
}
