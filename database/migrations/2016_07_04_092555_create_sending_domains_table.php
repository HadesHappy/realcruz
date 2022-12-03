<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendingDomainsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sending_domains', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('admin_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('name');
            $table->text('dkim_private');
            $table->text('dkim_public');
            $table->boolean('signing_enabled');
            $table->string('status');
            $table->integer('custom_order');

            $table->timestamps();

            // foreign
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('sending_domains');
    }
}
