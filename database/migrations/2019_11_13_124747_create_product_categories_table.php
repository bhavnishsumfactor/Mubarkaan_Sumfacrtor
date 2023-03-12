<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->increments('cat_id');
            $table->string('cat_name');
            $table->integer('cat_parent');
            $table->boolean('cat_publish')->default(true);
            $table->string('cat_tax_code')->nullable();
            $table->integer('cat_display_order');
            $table->integer('cat_created_by_id');
            $table->integer('cat_updated_by_id');
            $table->dateTime('cat_created_at');
            $table->dateTime('cat_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
