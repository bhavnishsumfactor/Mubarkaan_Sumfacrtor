<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_messages', function (Blueprint $table) {
            $table->bigIncrements('omsg_id');
            $table->boolean('omsg_type');
            $table->bigInteger('omsg_record_id');
            $table->bigInteger('omsg_subrecord_id');
            $table->boolean('omsg_from_type');
            $table->bigInteger('omsg_from_id');
            $table->text('omsg_comment');
            $table->dateTime('omsg_created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_messages');
    }
}
