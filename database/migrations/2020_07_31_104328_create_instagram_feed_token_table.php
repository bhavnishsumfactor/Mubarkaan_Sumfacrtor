<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramFeedTokenTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('instagram_feed_tokens', function (Blueprint $table) {
            $table->increments('iftoken_id');
            $table->string('iftoken_username');
            $table->string('iftoken_user_id')->nullable();
            $table->string('iftoken_access_code')->nullable();
            $table->date('iftoken_expired_at')->nullable();
            $table->dateTime('iftoken_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('instagram_feed_tokens');
    }
}
