<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFavouriteProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_favourite_products', function (Blueprint $table) {
            $table->bigIncrements('ufp_id');
            $table->bigInteger('ufp_user_id')->index();
            $table->bigInteger('ufp_prod_id')->index();
            $table->string('ufp_pov_code', 255)->nullable()->index();
            $table->dateTime('ufp_created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_favourite_products');
    }
}
