<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareEarnRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_earn_records', function (Blueprint $table) {
            $table->bigIncrements('ser_id');
            $table->tinyInteger('ser_type');
            $table->integer('ser_user_id');
            $table->string('ser_user_email');
            $table->string('ser_code');
            $table->dateTime('ser_created_at');
            $table->dateTime('ser_expiry');
            $table->tinyInteger('ser_publish')->defaul(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_earn_records');
    }
}
