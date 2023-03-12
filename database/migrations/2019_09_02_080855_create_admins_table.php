<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('admin_id');
            $table->string('admin_name');
            $table->string('admin_username')->unique();
            $table->string('admin_email')->unique();
            $table->integer('admin_phone_country_id')->default(0);
            $table->string('admin_phone', 50)->nullable();
            $table->string('admin_password');
            $table->integer('admin_default_lang')->default(1);
            $table->string('admin_default_timezone')->nullable();
            $table->integer('admin_role_id')->nullable();
            $table->boolean('admin_publish')->default(false);
            $table->string('admin_remember_token', 100)->nullable();
            $table->integer('admin_created_by_id');
            $table->integer('admin_updated_by_id');
            $table->dateTime('admin_created_at');
            $table->dateTime('admin_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
