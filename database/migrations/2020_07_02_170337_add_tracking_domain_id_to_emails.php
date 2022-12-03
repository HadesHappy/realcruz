<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackingDomainIdToEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->bigInteger('tracking_domain_id')->unsigned()->nullable();
            $table->foreign('tracking_domain_id')->references('id')->on('tracking_domains')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropForeign(['tracking_domain_id']);
            $table->dropColumn('tracking_domain_id');
        });
    }
}
