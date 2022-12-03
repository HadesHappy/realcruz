<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerificationStatusAndLastVerificationAtToSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->string('verification_status', 100)->nullable();
            $table->dateTime('last_verification_at')->nullable();
            $table->string('last_verification_by', 100)->nullable();
            $table->mediumText('last_verification_result')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropColumn('verification_status');
            $table->dropColumn('last_verification_at');
            $table->dropColumn('last_verification_by');
            $table->dropColumn('last_verification_result');
        });
    }
}
