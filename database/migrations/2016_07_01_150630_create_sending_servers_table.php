<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendingServersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sending_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('admin_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('bounce_handler_id')->unsigned()->nullable();
            $table->integer('feedback_loop_handler_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('type');
            $table->string('host')->nullable();
            $table->string('aws_access_key_id')->nullable();
            $table->string('aws_secret_access_key')->nullable();
            $table->string('aws_region')->nullable();
            $table->string('domain')->nullable(); // for Mailgun
            $table->string('api_key')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_protocol')->nullable();
            $table->string('sendmail_path')->nullable();
            $table->integer('quota_value');
            $table->integer('quota_base');
            $table->string('quota_unit');
            $table->string('status');
            $table->integer('custom_order');

            $table->timestamps();

            // foreign
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('customer_id', table('ss_customer_id_fk'))->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('bounce_handler_id', table('ss_bounce_handler_id_fk'))->references('id')->on('bounce_handlers')->onDelete('cascade');
            $table->foreign('feedback_loop_handler_id', table('ss_feedback_loop_handler_id_fk'))->references('id')->on('feedback_loop_handlers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('sending_servers');
    }
}
