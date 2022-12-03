<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('automation2_id')->unsigned();
            $table->string('subject');
            $table->string('from');
            $table->string('from_name');
            $table->string('reply_to');
            $table->text('content');

            $table->timestamps();

            $table->foreign('automation2_id')->references('id')->on('automation2s')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
}
