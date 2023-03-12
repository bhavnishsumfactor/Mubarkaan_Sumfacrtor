<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppReferralCodeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_referral_code_logs', function (Blueprint $table) {
            $table->string('arcl_ip_address');
            $table->string('arcl_referral_code');
            $table->string('arcl_device');
            $table->integer('arcl_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_referral_code_logs');
    }
}
