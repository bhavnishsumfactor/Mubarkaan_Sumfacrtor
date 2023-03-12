<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation_menus', function (Blueprint $table) {
            $table->increments('navmenu_id');
            $table->integer('navmenu_collection_id');
            $table->integer('navmenu_type');
            $table->string('navmenu_label', 150);
            $table->integer('navmenu_record_id')->default(0);
            $table->string('navmenu_url', 255)->nullable();
            $table->string('navmenu_target', 50)->default('_self');
            $table->integer('navmenu_display_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('navigation_menus');
    }
}
