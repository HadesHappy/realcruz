<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('admin_id')->unsigned()->nullable();
            $table->string('name');
            $table->longtext('content');
            $table->string('image');
            $table->integer('custom_order');
            $table->boolean('shared');

            $table->timestamps();

            // foreign
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('templates');
    }
}
