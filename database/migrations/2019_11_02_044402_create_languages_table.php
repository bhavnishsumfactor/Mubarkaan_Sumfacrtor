<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('lang_id');
            $table->string('lang_code')->unique();
            $table->string('lang_name');
            $table->string('lang_direction');
            $table->boolean('lang_publish')->default(true);
            $table->boolean('lang_default')->default(false);
            $table->boolean('lang_view_default')->default(false);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
