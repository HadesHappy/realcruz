<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailVerificationServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_verification_servers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('admin_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->text('name');
            $table->text('type');
            $table->text('options')->nullable();
            $table->string('status');

            $table->timestamps();

            // foreign
            $table->foreign('admin_id', table('evs_admin_id_fk'))->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('customer_id', table('evs_customer_id_fk'))->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_verification_servers');
    }
}
