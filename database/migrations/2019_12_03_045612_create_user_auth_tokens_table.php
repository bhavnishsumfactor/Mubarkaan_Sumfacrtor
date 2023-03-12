<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuthTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_auth_tokens', function (Blueprint $table) {
            $table->bigInteger('usrauth_user_id');
            $table->string('usrauth_token', 32);
            $table->datetime('usrauth_expiry');
            $table->text('usrauth_browser')->nullable();
            $table->datetime('usrauth_last_access')->nullable();
            $table->string('usrauth_last_ip', 16)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_auth_tokens');
    }
}
