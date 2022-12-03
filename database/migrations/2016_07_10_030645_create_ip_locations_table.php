<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpLocationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ip_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip_address');
            $table->string('country_code');
            $table->string('country_name');
            $table->string('region_code');
            $table->string('region_name');
            $table->string('city');
            $table->string('zipcode');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('metro_code');
            $table->string('areacode');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('ip_locations');
    }
}
