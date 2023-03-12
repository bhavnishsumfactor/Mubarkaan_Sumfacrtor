<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->bigIncrements('stpl_id');
            $table->string('stpl_slug', 150)->unique();
            $table->string('stpl_name', 255);
            $table->longText('stpl_body');
            $table->longText('stpl_default_body');
            $table->text('stpl_replacements');
            $table->string('stpl_priority', 20);
            $table->boolean('stpl_publish')->default(true);
            $table->integer('stpl_updated_by_id');
            $table->dateTime('stpl_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_templates');
    }
}
