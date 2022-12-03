<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Acelle\Model\Subscription;

class UpdateSubscriptionsKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create temporary table
        \DB::statement('DROP TABLE IF EXISTS _tmp_subscriptions');
        \DB::statement(sprintf('CREATE TABLE _tmp_subscriptions LIKE %s', table('subscriptions')));
        \DB::statement(sprintf('INSERT INTO _tmp_subscriptions SELECT * FROM %s', table('subscriptions')));

        // Clean up all subscription
        // $subscriptions = Subscription::all();
        // foreach ($subscriptions as $s) {
        //     $s->delete();
        // }

        // Columns to change
        $columns = ['customer_id', 'plan_id', 'user_id'];

        // Drop foreign keys
        foreach ($columns as $column) {
            try {
                Schema::table('subscriptions', function (Blueprint $table) use ($column) {
                    $table->dropForeign([$column]);
                });
            } catch (\Exception $ex) {
                // ignore
            }
        }

        // Drop columns
        foreach ($columns as $column) {
            try {
                Schema::table('subscriptions', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            } catch (\Exception $ex) {
                // ignore
            }
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('customer_id')->unsigned()->nullable();
            $table->integer('plan_id')->unsigned()->nullable();
        });

        \DB::statement(sprintf('
            UPDATE %s s 
            INNER JOIN _tmp_subscriptions t ON s.id = t.id 
            INNER JOIN %s p ON t.plan_id = p.uid 
            INNER JOIN %s c ON t.user_id = c.uid 
            SET s.plan_id = p.id, s.customer_id = c.id;
        ', table('subscriptions'), table('plans'), table('customers')));

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('customer_id')->unsigned()->change();
            $table->integer('plan_id')->unsigned()->change();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->renameColumn('customer_id', 'user_id');
        });
    }
}
