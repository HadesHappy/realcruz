<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerAminIdsToBlacklists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blacklists', function (Blueprint $table) {
            $table->integer('admin_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nullable();

            // foreign
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blacklists', function (Blueprint $table) {
            //
        });
    }
}
