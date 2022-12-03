<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingLogsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tracking_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('runtime_message_id')->unique()->nullable();
            $table->string('message_id')->unique()->nullable();
            $table->integer('customer_id')->unsigned(); // deliberate redundant for quick retrieving
            $table->integer('sending_server_id')->unsigned();
            $table->integer('campaign_id')->unsigned(); // deliberate redundant for quick retrieving
            $table->integer('subscriber_id')->unsigned(); // deliberate redundant for quick retrieving
            $table->string('status');
            $table->string('error')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('tracking_logs');
    }
}
