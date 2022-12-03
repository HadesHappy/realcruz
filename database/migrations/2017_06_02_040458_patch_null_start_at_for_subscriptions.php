<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatchNullStartAtForSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //$subscriptions =  \Acelle\Model\Subscription::whereNull('start_at')->get();
        //foreach ($subscriptions as $subscription) {
        //    $subscription->start_at = $subscription->created_at;
        //    $subscription->save();
        //}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // no roll-back
    }
}
