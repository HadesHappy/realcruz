<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('notifications', function (Blueprint $table) {
                $table->increments('id');
                $table->uuid('uid');
                $table->text('type');
                $table->text('title');
                $table->text('message');
                $table->text('level');
                $table->integer('admin_id')->unsigned()->nullable();
                $table->integer('customer_id')->unsigned()->nullable();

                $table->timestamps();

                $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            });
        } catch (\Exception $ex) {
            //
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notifications');
    }
}
