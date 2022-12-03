<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('options');
            $table->text('permissions');
            $table->integer('custom_order')->default(0);
            $table->integer('creator_id')->unsigned()->nullable();

            $table->timestamps();

            // foreign
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_groups');
    }
}
