<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thread_messages', function (Blueprint $table) {
            $table->bigIncrements('message_id');
            $table->integer('message_thread_id');
            $table->morphs('message_from');
			$table->integer('message_to');
			$table->longText('message_text');
			$table->tinyInteger('message_is_unread')->default('1');
			$table->tinyInteger('message_deleted');
			$table->dateTime('message_created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thread_messages');
    }
}
