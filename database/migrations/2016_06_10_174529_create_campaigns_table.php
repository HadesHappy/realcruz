<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('customer_id')->unsigned();
            $table->integer('mail_list_id')->unsigned()->nullable();
            $table->integer('segment_id')->unsigned()->nullable();
            $table->text('type');
            $table->text('name');
            $table->text('subject');
            $table->longtext('html');
            $table->longtext('plain');
            $table->text('from_email');
            $table->text('from_name');
            $table->text('reply_to');
            $table->text('status');
            $table->boolean('sign_dkim');
            $table->boolean('track_open');
            $table->boolean('track_click');
            $table->integer('resend');
            $table->integer('custom_order');
            $table->timestamp('run_at')->nullable();
            $table->timestamp('delivery_at')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('mail_list_id')->references('id')->on('mail_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('campaigns');
    }
}
