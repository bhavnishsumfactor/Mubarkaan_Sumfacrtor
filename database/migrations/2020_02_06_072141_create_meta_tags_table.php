<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetaTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_tags', function (Blueprint $table) {
            $table->bigIncrements('meta_id');
            $table->string('meta_controller');
            $table->string('meta_action');
            $table->integer('meta_record_id')->nullable();
            $table->integer('meta_subrecord_id')->nullable();
            $table->boolean('meta_default')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_other_meta_tags')->nullable(); 
            $table->integer('meta_created_by_id');
            $table->integer('meta_updated_by_id');
            $table->dateTime('meta_created_at');
            $table->dateTime('meta_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_tags');
    }
}
