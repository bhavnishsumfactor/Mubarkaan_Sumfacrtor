<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersRequestHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_request_histories', function (Blueprint $table) {
            $table->bigIncrements('ureq_id');
            $table->bigInteger('ureq_user_id');
            $table->tinyInteger('ureq_type');
            $table->longText('ureq_purpose');
            $table->dateTime('ureq_created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_request_histories');
    }
}
