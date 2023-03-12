<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_archives', function (Blueprint $table) {
            $table->bigIncrements('emailarchive_id');
            $table->string('emailarchive_to_email');
            $table->string('emailarchive_tpl_name');
            $table->text('emailarchive_subject');
            $table->text('emailarchive_body');
            $table->text('emailarchive_headers');
            $table->dateTime('emailarchive_created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_archives');
    }
}
