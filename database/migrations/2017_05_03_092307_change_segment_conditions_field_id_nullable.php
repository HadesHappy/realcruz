<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSegmentConditionsFieldIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('segment_conditions', function (Blueprint $table) {
            $table->integer('field_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('segment_conditions', function (Blueprint $table) {
            $table->integer('field_id')->unsigned()->nullable(false)->change();
        });
    }
}
