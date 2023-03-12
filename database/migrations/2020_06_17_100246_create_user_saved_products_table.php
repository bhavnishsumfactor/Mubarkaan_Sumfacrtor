<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSavedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_saved_products', function (Blueprint $table) {
            $table->bigIncrements('usp_id');
            $table->bigInteger('usp_user_id');
            $table->bigInteger('usp_prod_id');
            $table->string('usp_pov_code', 255)->nullable();
            $table->boolean('usp_temp')->default(false);
            $table->string('usp_session_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_saved_products');
    }
}
