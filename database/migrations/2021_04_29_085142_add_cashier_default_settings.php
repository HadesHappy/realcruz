<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashierDefaultSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Acelle\Model\Setting::set('recurring_charge_before_days', 2);
        \Acelle\Model\Setting::set('renew_free_plan', 'yes');
        \Acelle\Model\Setting::set('end_period_last_days', 10);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
