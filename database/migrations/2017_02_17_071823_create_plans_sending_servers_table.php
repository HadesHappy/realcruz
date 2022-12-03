<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansSendingServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans_sending_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sending_server_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->integer('fitness');

            $table->timestamps();

            $table->foreign('sending_server_id', table('pss_sending_server_id_fk'))->references('id')->on('sending_servers')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('plans_sending_servers');
    }
}
