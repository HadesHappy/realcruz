<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultFromEmailToSendingServers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sending_servers', function (Blueprint $table) {
            $table->string('default_from_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sending_servers', function (Blueprint $table) {
            $table->dropColumn('default_from_email');
        });
    }
}
