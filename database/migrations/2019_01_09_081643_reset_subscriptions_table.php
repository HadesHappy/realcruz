<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ResetSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function ($table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->string('user_id');
            $table->string('plan_id');
            $table->string('status');
            $table->timestamp('current_period_ends_at')->nullable();
            $table->timestampTz('trial_ends_at')->nullable();
            $table->timestampTz('ends_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
