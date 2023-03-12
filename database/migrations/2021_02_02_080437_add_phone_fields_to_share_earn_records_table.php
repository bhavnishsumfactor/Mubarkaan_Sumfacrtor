<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneFieldsToShareEarnRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('share_earn_records', function (Blueprint $table) {
            $table->string('ser_user_phone_code', 5)->nullable();
            $table->string('ser_user_phone', 20)->nullable();
            $table->string('ser_user_email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('share_earn_records', function (Blueprint $table) {
            $table->dropColumn('ser_user_phone_code');
            $table->dropColumn('ser_user_phone');
        });
    }
}
