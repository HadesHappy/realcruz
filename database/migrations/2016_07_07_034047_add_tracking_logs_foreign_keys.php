<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackingLogsForeignKeys extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tracking_logs', function (Blueprint $table) {
            // foreign
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('sending_server_id')->references('id')->on('sending_servers')->onDelete('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tracking_logs', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['sending_server_id']);
            $table->dropForeign(['campaign_id']);
            $table->dropForeign(['subscriber_id']);
        });
    }
}
