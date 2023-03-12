<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
          $table->bigIncrements('pkg_id');
          $table->tinyInteger('pkg_type');
          $table->string('pkg_slug', 150)->unique();
          $table->string('pkg_name');
          $table->boolean('pkg_card_type')->default(0);
          $table->boolean('pkg_publish')->default(1);
          $table->boolean('pkg_is_deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
