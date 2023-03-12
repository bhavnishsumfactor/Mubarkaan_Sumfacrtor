<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminPasswordResetsRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_password_reset_requests', function (Blueprint $table) {
            $table->integer('aprr_admin_id')->primary();
            $table->string('aprr_token');
            $table->string('aprr_otp', 10)->nullable();
            $table->dateTime('aprr_expiry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_password_reset_requests');
    }
}
