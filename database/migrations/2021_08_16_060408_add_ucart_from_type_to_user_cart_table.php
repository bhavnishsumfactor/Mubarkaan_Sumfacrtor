<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUcartFromTypeToUserCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_cart', function (Blueprint $table) {
            $table->integer('ucart_from_type', 4)->comment('1 => Web, 2=> Mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_cart', function (Blueprint $table) {
            $table->dropColumn('ucart_from_type');
        });
    }
}
