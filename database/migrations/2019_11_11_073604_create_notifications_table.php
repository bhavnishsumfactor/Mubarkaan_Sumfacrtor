<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('notify_id');
            $table->integer('notify_type');
            $table->bigInteger('notify_record_id');
            $table->bigInteger('notify_record_subid');
            $table->integer('notify_from_user_id');
            $table->text('notify_msg');
            $table->dateTime('notify_created_at');
            $table->dateTime('notify_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
