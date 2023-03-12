<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('collection_id');
            $table->integer('collection_page_id')->default(1);
            $table->string('collection_component_id', 100);
            $table->integer('collection_layout');
            $table->integer('collection_record_id')->default(0);
            $table->integer('collection_record_subid')->default(0);
            $table->integer('collection_display_order')->default(0);
            $table->index('collection_page_id');
            $table->index('collection_record_id');
            $table->index('collection_record_subid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections');
    }
}
