<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('layout_id')->unsigned();
            $table->integer('mail_list_id')->unsigned();
            $table->longtext('content');

            $table->timestamps();

            // foreign
            $table->foreign('mail_list_id')->references('id')->on('mail_lists')->onDelete('cascade');
            $table->foreign('layout_id')->references('id')->on('layouts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
