<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSendingDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sending_domains', function (Blueprint $table) {
            $table->text('verification_hostname')->default(null)->nullable()->change();
            $table->text('dkim_selector')->default(null)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sending_domains', function (Blueprint $table) {
            //
        });
    }
}
