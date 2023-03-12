<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderReturnBankInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_return_bank_info', function (Blueprint $table) {
            $table->bigInteger('orbinfo_order_id');
            $table->bigInteger('orbinfo_user_id');
            $table->string('orbinfo_name');
            $table->string('orbinfo_branch');
            $table->string('orbinfo_account_name');
            $table->string('orbinfo_branch_code');
            $table->string('orbinfo_account_number');
            $table->text('orbinfo_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_return_bank_info');
    }
}
