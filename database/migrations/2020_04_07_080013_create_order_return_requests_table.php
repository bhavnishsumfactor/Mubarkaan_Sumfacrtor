<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReturnRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_return_requests', function (Blueprint $table) {
			$table->bigIncrements('orrequest_id'); 
			$table->bigInteger('orrequest_user_id'); 
			$table->bigInteger('orrequest_op_id'); 
			$table->bigInteger('orrequest_order_id'); 
			$table->bigInteger('orrequest_type'); 
			$table->integer('orrequest_qty'); 
			$table->dateTime('orrequest_date'); 
			$table->boolean('orrequest_status')->default(false); 
			$table->boolean('orrequest_is_shipping')->default(false); 
			$table->boolean('orrequest_order_status')->default(false); 
			$table->bigInteger('orrequest_omsg_id'); 
			$table->string('orrequest_txn_gateway_transaction_id'); 
			$table->text('orrequest_reason')->nullable();
			$table->text('orrequest_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_return_requests');
    }
}
