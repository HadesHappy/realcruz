<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->integer('subscription_id')->unsigned();
            $table->integer('transaction_id')->unsigned()->nullable();
            $table->string('type');
            $table->text('data');

            $table->timestamps();

            $table->foreign('subscription_id', table('sl_subscription_id_fk'))->references('id')->on('subscriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_logs');
    }
}
