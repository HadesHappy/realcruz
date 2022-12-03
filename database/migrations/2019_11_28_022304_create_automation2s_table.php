<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutomation2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automation2s', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->string('name');
            $table->integer('customer_id')->unsigned();
            $table->integer('mail_list_id')->unsigned();
            $table->string('time_zone');
            $table->string('status');
            $table->text('data');

            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('mail_list_id')->references('id')->on('mail_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('automation2s');
    }
}
