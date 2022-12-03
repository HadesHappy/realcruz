<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrlToCampaignLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_links', function (Blueprint $table) {
            $table->text('url');
        });

        try {
            Schema::table('campaign_links', function (Blueprint $table) {
                $table->dropForeign(['link_id']);
            });
        } catch (\Exception $ex) {
            // ignore
        }

        try {
            Schema::table('campaign_links', function (Blueprint $table) {
                $table->dropColumn(['link_id']);
            });
        } catch (\Exception $ex) {
            // ignore
        }

        Schema::dropIfExists('links');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_links', function (Blueprint $table) {
            //
        });
    }
}
