<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimonialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->increments('testimonial_id');
            $table->string('testimonial_user_name', 255);
            $table->string('testimonial_designation', 255)->default('')->nullable();
            $table->string('testimonial_title', 255);
            $table->longText('testimonial_description');
            $table->boolean('testimonial_publish')->default(true);
            $table->integer('testimonial_created_by_id');
            $table->integer('testimonial_updated_by_id');
            $table->dateTime('testimonial_created_at');
            $table->dateTime('testimonial_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testimonials');
    }
}
