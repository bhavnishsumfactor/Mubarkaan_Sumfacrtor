<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('currency_id');
            $table->string('currency_name', 100)->unique();
            $table->string('currency_code', 10)->unique();
            $table->string('currency_symbol', 10);
            $table->boolean('currency_position')->default(false);
            $table->decimal('currency_value', 20, 8);
            $table->boolean('currency_publish')->default(true);
            $table->boolean('currency_default')->default(false);
            $table->boolean('currency_view_default')->default(false);
            $table->dateTime('currency_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
