<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_metas', function (Blueprint $table) {
            $table->bigInteger('cmeta_collection_id');
            $table->string('cmeta_key', 100);
            $table->text('cmeta_value')->nullable();
            $table->primary(array('cmeta_collection_id', 'cmeta_key'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_metas');
    }
}
