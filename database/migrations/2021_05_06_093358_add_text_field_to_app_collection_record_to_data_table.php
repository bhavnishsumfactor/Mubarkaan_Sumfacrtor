<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTextFieldToAppCollectionRecordToDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_collection_record_to_data', function (Blueprint $table) {
            $table->text('acrd_description')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_collection_record_to_data', function (Blueprint $table) {
            $table->dropColumn('acrd_description');
        });
    }
}
