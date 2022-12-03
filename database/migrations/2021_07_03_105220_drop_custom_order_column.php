<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCustomOrderColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['sending_servers', 'customer_groups', 'templates', 'fields', 'bounce_handlers', 'mail_lists', 'plans', 'admin_groups', 'sending_domains', 'feedback_loop_handlers', 'campaigns'];
        foreach ($tables as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropColumn('custom_order');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sending_servers', function (Blueprint $table) {
            //
        });
    }
}
