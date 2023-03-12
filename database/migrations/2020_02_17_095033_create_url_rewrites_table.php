<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlRewritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_rewrites', function (Blueprint $table) {
            $table->bigIncrements('urlrewrite_id');
            $table->integer('urlrewrite_type');
            $table->integer('urlrewrite_record_id')->nullable()->index();
            $table->string('urlrewrite_original', 255);
            $table->string('urlrewrite_custom', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_rewrites');
    }
}
