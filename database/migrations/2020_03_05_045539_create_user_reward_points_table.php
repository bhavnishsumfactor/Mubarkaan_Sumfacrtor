<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRewardPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_reward_points', function (Blueprint $table) {
            $table->bigIncrements('urp_id');
            $table->bigInteger('urp_user_id');
            $table->bigInteger('urp_referral_user_id');
            $table->integer('urp_points');
            $table->text('urp_comments');
            $table->integer('urp_type');
            $table->bigInteger('urp_order_id');
            $table->datetime('urp_date_added');
            $table->datetime('urp_date_expiry');
            $table->boolean('urp_used')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_reward_points');
    }
}
