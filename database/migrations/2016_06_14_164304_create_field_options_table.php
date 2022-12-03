<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldOptionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('field_options', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('field_id')->unsigned();
            $table->string('label');
            $table->string('value');

            $table->timestamps();

            // foreign
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('field_options');
    }
}
