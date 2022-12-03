<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Acelle\Model\SendingServer;

class UpdateSparkpostSendingServers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sparkies = SendingServer::where('type', 'sparkpost-api')->whereNull('host')->get();
        foreach ($sparkies as $sparky) {
            $sparky->host = 'api.sparkpost.com';
            $sparky->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sending_servers', function (Blueprint $table) {
            //
        });
    }
}
