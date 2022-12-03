<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserDetailsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('first_name')->text()->nullable();
            $table->text('last_name')->text()->nullable();
        });

        \DB::statement(sprintf(
            '
            UPDATE %s u SET 
            first_name = (SELECT first_name FROM %s c WHERE u.customer_id = c.id LIMIT 1),
            last_name = (SELECT last_name FROM %s c WHERE u.customer_id = c.id LIMIT 1)',
            table('users'),
            table('customers'),
            table('customers')
        ));

        \DB::statement(sprintf(
            '
            UPDATE %s u SET 
            first_name = (SELECT first_name FROM %s a WHERE a.user_id = u.id LIMIT 1),
            last_name = (SELECT last_name FROM %s a WHERE a.user_id = u.id LIMIT 1)
            WHERE u.customer_id IS NULL;',
            table('users'),
            table('admins'),
            table('admins')
        ));

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
