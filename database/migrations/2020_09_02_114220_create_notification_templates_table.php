<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->bigIncrements('ntpl_id');
            $table->string('ntpl_slug', 150)->unique();
            $table->string('ntpl_name', 255);
            $table->longText('ntpl_body');
            $table->longText('ntpl_default_body');
            $table->text('ntpl_replacements');
            $table->string('ntpl_priority', 20);
            $table->boolean('ntpl_publish')->default(true);
            $table->integer('ntpl_updated_by_id');
            $table->dateTime('ntpl_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_templates');
    }
}
