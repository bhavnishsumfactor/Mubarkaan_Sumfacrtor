<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVarientIdFieldToDigitalFileRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('digital_file_records', function (Blueprint $table) {
            $table->bigInteger('dfr_pov_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('digital_file_records', function (Blueprint $table) {
            $table->dropColumn('dfr_pov_id');
        });
    }
}
