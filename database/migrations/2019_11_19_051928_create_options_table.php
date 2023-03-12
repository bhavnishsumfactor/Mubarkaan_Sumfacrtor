<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->increments('option_id');
            $table->string('option_name');
            $table->boolean('option_type')->default(false);
            $table->boolean('option_has_image')->default(false);
            $table->boolean('option_has_sizechart')->default(false);
            $table->integer('option_created_by_id');
            $table->integer('option_updated_by_id');
            $table->dateTime('option_created_at');
            $table->dateTime('option_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options');
    }
}
