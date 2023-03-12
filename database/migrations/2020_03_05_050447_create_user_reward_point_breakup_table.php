<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRewardPointBreakupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_reward_point_breakup', function (Blueprint $table) {
            $table->bigIncrements('urpbreakup_id');
            $table->bigInteger('urpbreakup_urp_id');
            $table->integer('urpbreakup_points');
            $table->datetime('urpbreakup_expiry');
            $table->boolean('urpbreakup_used')->default(false);
            $table->bigInteger('urpbreakup_referral_user_id');
            $table->bigInteger('urpbreakup_used_order_id');
            $table->datetime('urpbreakup_used_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_reward_point_breakup');
    }
}
