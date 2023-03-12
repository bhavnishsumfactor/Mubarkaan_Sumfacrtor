<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->bigIncrements('etpl_id');
            $table->string('etpl_slug', 255);
            $table->string('etpl_subject', 255);
            $table->longText('etpl_body');
            $table->longText('etpl_default_body');
            $table->text('etpl_replacements');
            $table->string('etpl_priority', 20);
            $table->integer('etpl_updated_by_id');
            $table->dateTime('etpl_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_templates');
    }
}
