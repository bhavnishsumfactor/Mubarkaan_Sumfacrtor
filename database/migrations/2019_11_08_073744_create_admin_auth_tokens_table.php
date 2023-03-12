<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminAuthTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_auth_tokens', function (Blueprint $table) {
            $table->bigInteger('admauth_admin_id');
            $table->string('admauth_token', 32);
            $table->datetime('admauth_expiry');
            $table->text('admauth_browser');
            $table->datetime('admauth_last_access');
            $table->string('admauth_last_ip', 16);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_auth_tokens');
    }
}
