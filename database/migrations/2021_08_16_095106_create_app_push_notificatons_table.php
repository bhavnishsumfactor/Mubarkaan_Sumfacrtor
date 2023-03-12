<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppPushNotificatonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_push_notificatons', function (Blueprint $table) {
            $table->bigIncrements('apn_id');
            $table->bigInteger('apn_user_id');
            $table->string('apn_type');
            $table->string('apn_title');
            $table->text('apn_body');
            $table->text('apn_extra');
            $table->integer('apn_status');
            $table->dateTime('apn_created_on');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_push_notificatons');
    }
}
