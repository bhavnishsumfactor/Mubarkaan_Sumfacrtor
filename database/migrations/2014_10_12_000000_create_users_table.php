<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('user_facebook_id', 50)->nullable();
            $table->string('user_google_id', 50)->nullable();
            $table->string('user_instagram_id', 50)->nullable();
            $table->string('user_fname', 100)->nullable();
            $table->string('user_lname', 100)->nullable();
            $table->string('user_dob', 100)->default('');
            $table->string('user_gender', 100)->nullable();
            $table->string('user_email')->nullable();
            $table->integer('user_phone_country_id')->default(0);
            $table->string('user_phone')->nullable();
            $table->string('user_password')->nullable();
            $table->string('user_timezone', 100)->nullable();
            $table->integer('user_country_id')->nullable();
            $table->integer('user_language_id')->nullable();
            $table->integer('user_currency_id')->nullable();
            $table->boolean('user_phone_verified')->default(false);
            $table->boolean('user_email_verified')->default(false);
            $table->string('user_referral_code', 191)->nullable();
            $table->boolean('user_publish')->default(true);
            $table->dateTime('user_created_at');
            $table->dateTime('user_updated_at');
            $table->integer('user_created_by_id');
            $table->integer('user_updated_by_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
