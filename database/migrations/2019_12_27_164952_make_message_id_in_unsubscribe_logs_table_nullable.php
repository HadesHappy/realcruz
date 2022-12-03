<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeMessageIdInUnsubscribeLogsTableNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unsubscribe_logs', function (Blueprint $table) {
            $table->dropForeign(['message_id']);
        });

        Schema::table('unsubscribe_logs', function (Blueprint $table) {
            $table->string('message_id')->nullable()->change();
        });

        Schema::table('unsubscribe_logs', function (Blueprint $table) {
            $table->foreign('message_id')->references('message_id')->on('tracking_logs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unsubscribe_logs', function (Blueprint $table) {
            //
        });
    }
}
