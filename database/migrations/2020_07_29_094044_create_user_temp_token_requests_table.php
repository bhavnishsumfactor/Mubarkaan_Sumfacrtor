<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTempTokenRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_temp_token_requests', function (Blueprint $table) {
            $table->bigIncrements('uttr_id');
            $table->integer('uttr_type');
            $table->integer('uttr_user_id');
            $table->string('uttr_code', 255);
            $table->dateTime('uttr_expiry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_temp_token_requests');
    }
}
