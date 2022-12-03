<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('open_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id');
            $table->string('ip_address');

            $table->text('user_agent');

            $table->timestamps();

            // foreign
            $table->foreign('message_id')->references('message_id')->on('tracking_logs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('open_logs');
    }
}
