<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_webhooks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('type');
            $table->text('endpoint');
            $table->integer('campaign_id')->unsigned();
            $table->integer('campaign_link_id')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('campaign_link_id')->references('id')->on('campaign_links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_webhooks');
    }
}
