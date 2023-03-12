<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_to_categories', function (Blueprint $table) {
            $table->bigInteger('ptc_prod_id');
            $table->bigInteger('ptc_cat_id');
            $table->primary(['ptc_prod_id', 'ptc_cat_id']);
            $table->index(['ptc_prod_id', 'ptc_cat_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_to_categories');
    }
}
