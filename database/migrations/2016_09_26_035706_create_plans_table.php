<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('admin_id')->unsigned()->nullable();
            $table->integer('currency_id')->unsigned();
            $table->string('name');
            $table->decimal('price', 16, 2);
            $table->string('frequency_amount');
            $table->string('frequency_unit');
            $table->text('options');
            $table->string('status');
            $table->string('color');
            $table->integer('custom_order');
            $table->boolean('is_default')->default(false);

            $table->timestamps();

            // foreign
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('plans');
    }
}
