<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDarkModeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('dark_mode');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->string('theme_mode')->default('light');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('dark_mode');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->string('theme_mode')->default('light');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
