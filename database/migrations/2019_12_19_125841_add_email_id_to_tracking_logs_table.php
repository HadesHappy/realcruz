<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailIdToTrackingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracking_logs', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);

            $table->integer('email_id')->unsigned()->nullable();
            $table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');
        });

        Schema::table('tracking_logs', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned()->nullable()->change();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracking_logs', function (Blueprint $table) {
            //
        });
    }
}
