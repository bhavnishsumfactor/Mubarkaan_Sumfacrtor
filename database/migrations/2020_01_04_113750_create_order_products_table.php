    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('op_id');
            $table->bigInteger('op_order_id');
            $table->integer('op_qty');
            $table->bigInteger('op_product_id');
            $table->boolean('op_is_pickup')->default(false);
            $table->string('op_product_name');
            $table->integer('op_product_type');
            $table->datetime('op_expired_on')->nullable();
            $table->decimal('op_product_price', 10, 2);
            $table->string('op_pov_code')->nullable();
            $table->integer('op_return_age')->nullable();
            $table->text('op_product_variants')->nullable();
            $table->string('op_product_sku')->nullable();
            $table->string('op_product_width')->nullable();
            $table->string('op_product_height')->nullable();
            $table->string('op_product_weight')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
