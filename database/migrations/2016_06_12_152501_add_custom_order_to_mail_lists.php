<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomOrderToMailLists extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('mail_lists', function (Blueprint $table) {
            $table->integer('custom_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('mail_lists', function (Blueprint $table) {
            $table->dropColumn('custom_order');
        });
    }
}
