<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppNotificationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_notification_templates', function (Blueprint $table) {
            $table->bigIncrements('antpl_id');
            $table->string('antpl_slug', 150)->unique();
            $table->string('antpl_name', 255);
            $table->longText('antpl_body');
            $table->longText('antpl_default_body');
            $table->text('antpl_replacements');
            $table->string('antpl_priority', 20);
            $table->boolean('antpl_publish')->default(true);
            $table->integer('antpl_updated_by_id');
            $table->dateTime('antpl_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_notification_templates');
    }
}
