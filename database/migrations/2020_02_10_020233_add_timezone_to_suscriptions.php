<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimezoneToSuscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE ' . \DB::getTablePrefix() . 'subscriptions CHANGE `ends_at` `ends_at` datetime');
        DB::statement('ALTER TABLE ' . \DB::getTablePrefix() . 'subscriptions CHANGE `current_period_ends_at` `current_period_ends_at` datetime');

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('timezone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
        });
    }
}
