<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMailListDescriptionToRemindMessage extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('mail_lists', function (Blueprint $table) {
            $table->renameColumn('description', 'remind_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('mail_lists', function (Blueprint $table) {
            $table->renameColumn('remind_message', 'description');
        });
    }
}
