<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSegmentConditionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('segment_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('segment_id')->unsigned();
            $table->integer('field_id')->unsigned();
            $table->string('operator');
            $table->string('value');

            $table->timestamps();

            // foreign
            $table->foreign('segment_id')->references('id')->on('segments')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('segment_conditions');
    }
}
