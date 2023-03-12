<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionVarientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_option_varients', function (Blueprint $table) {
            $table->bigIncrements('pov_id');
            $table->text('pov_code');
            $table->text('pov_display_title');
            $table->decimal('pov_price', 10, 2)->nullable();
            $table->bigInteger('pov_quantity');
            $table->bigInteger('pov_prod_id')->index();
            $table->string('pov_sku');
            $table->boolean('pov_publish');
            $table->boolean('pov_default')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_option_varients');
    }
}
