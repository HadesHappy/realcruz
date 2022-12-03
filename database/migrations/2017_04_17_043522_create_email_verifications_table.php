<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('result', 20);
            $table->text('details');
            $table->integer('subscriber_id')->unsigned();
            $table->integer('email_verification_server_id')->unsigned();
            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
            $table->foreign('email_verification_server_id', table('ev_email_verification_server_id_fk'))->references('id')->on('email_verification_servers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_verifications');
    }
}
