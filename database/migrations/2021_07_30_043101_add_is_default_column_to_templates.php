<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDefaultColumnToTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->boolean('is_default')->default(false)->nullable();
        });

        // DROP admin id by foreign key name (for DB initiated by older versions when key does not have prefix)
        try {
            Schema::table('templates', function (Blueprint $table) {
                $table->dropForeign('templates_admin_id_foreign');
            });
        } catch (\Exception $ex) {
            // Just ignore
        }

        // DROP admin id by foreign key name (for DB initiated by latest versions)
        try {
            Schema::table('templates', function (Blueprint $table) {
                $table->dropForeign(['admin_id']);
            });
        } catch (\Exception $ex) {
            // Just ignore
        }

        // Drop column itself
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn(['admin_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            //
        });
    }
}
