<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('transactions', function (Blueprint $table) {
             $table->bigIncrements('txn_id');
             $table->bigInteger('txn_user_id');
             $table->dateTime('txn_date');
             $table->decimal('txn_amount', 10, 2);
             $table->text('txn_gateway_transaction_id');
             $table->string('txn_gateway_type');
             $table->longText('txn_gateway_response');
             $table->text('txn_comments');
             $table->boolean('txn_status');
             $table->bigInteger('txn_order_id');
             $table->boolean('txn_type');
         });
    }
     /**

     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
