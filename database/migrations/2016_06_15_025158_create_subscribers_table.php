<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('mail_list_id')->unsigned();
            $table->string('email');
            $table->string('status');
            $table->string('from');
            $table->string('ip');

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
        Schema::drop('subscribers');
    }
}
