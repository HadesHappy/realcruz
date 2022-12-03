<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSegmentForeignKeyToCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->foreign('segment_id')->references('id')->on('segments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropForeign(['segment_id']);
        });
    }
}
