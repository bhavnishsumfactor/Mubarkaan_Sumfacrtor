<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_collections', function (Blueprint $table) {
            $table->bigIncrements('ac_id');
            $table->integer('ac_page_id')->default(1);
            $table->integer('ac_type');
            $table->bigInteger('ac_display_order');
            $table->index('ac_page_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_collections');
    }
}
