<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerGroupSendingServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_group_sending_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sending_server_id');
            $table->string('customer_group_id');
            $table->integer('fitness');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_group_sending_servers');
    }
}
