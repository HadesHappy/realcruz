<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('mail_list_id')->unsigned();
            $table->string('label');
            $table->string('type');
            $table->string('tag');
            $table->string('default_value')->nullable();
            $table->boolean('visible');
            $table->boolean('required');
            $table->integer('custom_order');

            $table->timestamps();

            // foreign
            $table->foreign('mail_list_id')->references('id')->on('mail_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('fields');
    }
}
