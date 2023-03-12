<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppExploresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_explores', function (Blueprint $table) {
            $table->increments('ae_id');
            $table->integer('ae_app_page_id');
            $table->integer('ae_type');
            $table->integer('ae_display_order');
            $table->string('ae_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_explores');
    }
}
