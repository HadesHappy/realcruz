<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->text('tax_number')->nullable()->change();
            $table->text('billing_address')->nullable()->change();
        });

        Schema::table('mail_lists', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
        });

        Schema::table('fields', function (Blueprint $table) {
            $table->integer('custom_order')->nullable()->change();
        });

        Schema::table('subscribers', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
            $table->text('from')->nullable()->change();
            $table->text('ip')->nullable()->change();
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->text('subject')->nullable()->change();
            $table->longText('html')->nullable()->change();
            $table->longText('plain')->nullable()->change();
            $table->text('from_email')->nullable()->change();
            $table->text('from_name')->nullable()->change();
            $table->text('reply_to')->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->boolean('sign_dkim')->nullable()->change();
            $table->boolean('track_open')->nullable()->change();
            $table->boolean('track_click')->nullable()->change();
            $table->integer('resend')->nullable()->change();
            $table->integer('custom_order')->nullable()->change();
            $table->string('template_source')->nullable()->change();
            $table->text('image')->nullable()->change();
        });

        Schema::table('templates', function (Blueprint $table) {
            $table->longText('content')->nullable()->change();
            $table->text('image')->nullable()->change();
            $table->integer('custom_order')->nullable()->change();
            $table->integer('shared')->nullable()->change();
            $table->string('source')->nullable()->change();
        });

        Schema::table('admin_groups', function (Blueprint $table) {
            $table->text('options')->nullable()->change();
            $table->text('permissions')->nullable()->change();
            $table->integer('custom_order')->nullable()->change();
        });

        Schema::table('languages', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
            $table->string('region_code')->nullable()->change();
            $table->string('code')->nullable()->change();
        });

        Schema::table('click_logs', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->change();
            $table->text('user_agent')->nullable()->change();
        });

        Schema::table('open_logs', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->change();
            $table->text('user_agent')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail_lists', function (Blueprint $table) {
            //
        });
    }
}
