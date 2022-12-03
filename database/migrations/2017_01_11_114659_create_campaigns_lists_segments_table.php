<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsListsSegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns_lists_segments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id')->unsigned();
            $table->integer('mail_list_id')->unsigned();
            $table->integer('segment_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('campaign_id', table('cls_campaign_id_fk'))->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('mail_list_id', table('cls_mail_list_id_fk'))->references('id')->on('mail_lists')->onDelete('cascade');
            $table->foreign('segment_id', table('cls_segment_id_fk'))->references('id')->on('segments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('campaigns_lists_segments');
    }
}
