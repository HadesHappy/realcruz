<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteListSegmnetColsFormCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropForeign(['mail_list_id']);
            $table->dropColumn('mail_list_id');

            $table->dropForeign(['segment_id']);
            $table->dropColumn('segment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->integer('mail_list_id')->unsigned()->nullable();
            $table->integer('segment_id')->unsigned()->nullable();

            $table->foreign('mail_list_id')->references('id')->on('mail_lists')->onDelete('cascade');
            $table->foreign('segment_id')->references('id')->on('segments')->onDelete('cascade');
        });
    }
}
