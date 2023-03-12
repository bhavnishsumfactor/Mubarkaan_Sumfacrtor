<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxGroupTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_group_types', function (Blueprint $table) {
            $table->bigIncrements('tgtype_id');
            $table->Integer('tgtype_tgroup_id');
            $table->string('tgtype_name');
            $table->string('tgtype_rate');
            $table->Integer('tgtype_state_type');
            $table->Integer('tgtype_country_id');
            $table->boolean('tgtype_combined')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_group_types');
    }
}
