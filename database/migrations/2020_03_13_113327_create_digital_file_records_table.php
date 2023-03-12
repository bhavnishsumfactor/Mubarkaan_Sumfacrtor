<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalFileRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_file_records', function (Blueprint $table) {
            $table->bigInteger('dfr_record_id');
            $table->bigInteger('dfr_subrecord_id')->nullable();
            $table->bigInteger('dfr_record_type');
            $table->bigInteger('dfr_file_type');
            $table->bigInteger('dfr_afile_id')->nullable();
            $table->string('dfr_name')->nullable();
            $table->string('dfr_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('digital_file_records');
    }
}
