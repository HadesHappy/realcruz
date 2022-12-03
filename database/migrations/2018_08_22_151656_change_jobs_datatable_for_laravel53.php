<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeJobsDatatableForLaravel53 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex(['queue_reserved_reserved_at']);
            $table->dropColumn('reserved');
            $table->index(['queue', 'reserved_at']);
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->longText('exception')->after('payload');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->tinyInteger('reserved')->unsigned();
            $table->index(['queue', 'reserved', 'reserved_at']);
            $table->dropIndex(['queue_reserved_at']);
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->dropColumn('exception');
        });
    }
}
