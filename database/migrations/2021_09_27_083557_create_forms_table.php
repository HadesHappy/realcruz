<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('name');
            $table->integer('customer_id')->unsigned();
            $table->integer('mail_list_id')->unsigned()->nullable();
            $table->integer('template_id')->unsigned()->nullable();
            $table->longText('metadata')->nullable();
            $table->string('status');
            $table->timestamps();


            $table->foreign('template_id')->references('id')->on('templates')->onDelete('set null');
            $table->foreign('mail_list_id')->references('id')->on('mail_lists')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms');
    }
}
