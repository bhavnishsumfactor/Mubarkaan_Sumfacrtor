<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->bigIncrements('ugroup_id');
            $table->string('ugroup_name', 150)->unique();
            $table->integer('ugroup_created_by_id');
            $table->integer('ugroup_updated_by_id');
            $table->dateTime('ugroup_created_at');
            $table->dateTime('ugroup_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_groups');
    }
}
