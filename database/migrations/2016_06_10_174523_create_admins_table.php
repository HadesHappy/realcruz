<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('user_id')->unsigned();
            $table->integer('creator_id')->unsigned()->nullable();
            $table->integer('contact_id')->unsigned()->nullable();
            $table->integer('admin_group_id')->unsigned();
            $table->integer('language_id')->unsigned()->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('image')->nullable();
            $table->string('timezone');
            $table->string('status')->nullable();
            $table->string('color_scheme')->nullable();

            $table->timestamps();

            // foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('admin_group_id')->references('id')->on('admin_groups')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admins');
    }
}
