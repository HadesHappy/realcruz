<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFieldsNullable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->string('type')->nullable()->change();
            $table->string('description')->nullable()->change();
        });

        Schema::table('ip_locations', function (Blueprint $table) {
            $table->string('country_code')->nullable()->change();
            $table->string('country_name')->nullable()->change();
            $table->string('region_code')->nullable()->change();
            $table->string('region_name')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('zipcode')->nullable()->change();
            $table->string('latitude')->nullable()->change();
            $table->string('longitude')->nullable()->change();
            $table->string('metro_code')->nullable()->change();
            $table->string('areacode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plugins', function (Blueprint $table) {
            //
        });
    }
}
