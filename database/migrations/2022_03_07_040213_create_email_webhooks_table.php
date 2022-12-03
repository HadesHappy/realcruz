<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_webhooks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('type');
            $table->text('endpoint');
            $table->integer('email_id')->unsigned();
            $table->integer('email_link_id')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');
            $table->foreign('email_link_id')->references('id')->on('email_links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_webhooks');
    }
}
