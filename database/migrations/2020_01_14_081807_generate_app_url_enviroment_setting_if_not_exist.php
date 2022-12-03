<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GenerateAppUrlEnviromentSettingIfNotExist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Just return if running in console (when $request object is unknown)
        if (App::runningInConsole()) {
            return;
        }

        \Acelle\Helpers\reset_app_url();
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
