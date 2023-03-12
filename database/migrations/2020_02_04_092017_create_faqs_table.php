<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->bigIncrements('faq_id');
            $table->integer('faq_faqcat_id');
            $table->string('faq_title');
            $table->string('faq_content');
            $table->boolean('faq_publish')->default(1);
            $table->integer('faq_display_order')->default(0); 
            $table->integer('faq_created_by_id');
            $table->integer('faq_updated_by_id');
            $table->dateTime('faq_created_at');
            $table->dateTime('faq_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faqs');
    }
}
