<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxGroupCombinedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_group_combined', function (Blueprint $table) {
            $table->bigInteger('tgc_tgtype_id');
            $table->string('tgc_name');
            $table->string('tgc_rate');
            $table->boolean('tgc_applied_on')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_group_combined');
    }
}
