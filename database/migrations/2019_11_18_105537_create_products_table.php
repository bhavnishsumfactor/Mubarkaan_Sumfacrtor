<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('prod_id');
            $table->string('prod_name');
            $table->integer('prod_type');
            $table->integer('prod_brand_id')->nullable()->index();
            $table->integer('prod_taxcat_id')->index();
            $table->string('prod_cost')->nullable();
            $table->decimal('prod_price', 10, 2)->nullable();
            $table->string('prod_stock')->nullable();
            $table->string('prod_model')->nullable();
            $table->integer('prod_condition')->nullable()->default(false);
            $table->boolean('prod_published')->default(false);
            $table->datetime('prod_publish_from')->nullable();
            $table->boolean('prod_stock_out_sellable')->default(false);
            $table->boolean('prod_cod_available')->default(false);
            $table->boolean('prod_self_pickup')->default(false);
            $table->integer('prod_min_order_qty')->nullable();
            $table->integer('prod_from_origin_country_id')->nullable();
            $table->integer('prod_sbpkg_id')->nullable();
            $table->integer('prod_max_order_qty')->nullable();
            $table->longText('prod_description')->nullable();
            $table->integer('prod_sold_count')->default(0);
            $table->integer('prod_created_by_id');
            $table->integer('prod_updated_by_id');
            $table->dateTime('prod_created_on');
            $table->datetime('prod_updated_on');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
