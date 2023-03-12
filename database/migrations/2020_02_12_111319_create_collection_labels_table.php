<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_labels', function (Blueprint $table) {
            $table->string('collection_component_id', 100);
            $table->string('collection_title', 255)->nullable();
            $table->string('collection_caption', 255)->nullable();
            $table->integer('collection_featured_product')->nullable();
            $table->primary('collection_component_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_labels');
    }
}
