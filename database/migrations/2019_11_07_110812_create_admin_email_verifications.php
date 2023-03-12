<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminEmailVerifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_email_verifications', function (Blueprint $table) {
            $table->integer('aev_admin_id')->primary();
            $table->string('aev_token', 255);
            $table->string('aev_email', 255);
            $table->datetime('aev_expiry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_email_verifications');
    }
}
