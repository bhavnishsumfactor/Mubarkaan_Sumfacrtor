<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFileTypeDigitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_file_type_digitals', function (Blueprint $table) {
            $table->bigInteger('pftd_prod_id');
            $table->bigInteger('pftd_afile_id');
            $table->bigInteger('pftd_file_type');
            $table->string('pftd_name');
            $table->string('pftd_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_file_type_digitals');
    }
}
