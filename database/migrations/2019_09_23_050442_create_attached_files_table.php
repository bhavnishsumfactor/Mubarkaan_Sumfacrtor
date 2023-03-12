<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachedFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attached_files', function (Blueprint $table) {
            $table->bigIncrements('afile_id');
            $table->integer('afile_type')->comment('section id from constants')->index();
            $table->integer('afile_record_id')->index();
            $table->integer('afile_record_subid')->default(0)->index();
            $table->integer('afile_device')->comment('1=>Desktop,2=>Ipad/Tablet,3=>Mobile')->default(0);
            $table->string('afile_physical_path', 250);
            $table->string('afile_name', 200);
            $table->integer('afile_display_order')->default(1);
            $table->string('afile_attribute_title')->nullable();
            $table->string('afile_attribute_alt')->nullable();
            $table->dateTime('afile_created_at');
            $table->dateTime('afile_updated_at');
            $table->integer('afile_updated_by_id');
            $table->dateTime('afile_meta_updated_at');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attached_files');
    }
}
