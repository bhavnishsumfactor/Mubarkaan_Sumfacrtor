<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsletterSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('subscription_id');
            $table->integer('subscription_provider');
            $table->string('subscription_list_id');
            $table->string('subscription_email');
            $table->boolean('subscription_optin');
            $table->string('subscription_token', 191)->nullable();
            $table->dateTime('subscription_expiry')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newsletter_subscriptions');
    }
}
