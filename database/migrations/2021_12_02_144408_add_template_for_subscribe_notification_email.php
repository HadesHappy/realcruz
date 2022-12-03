<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateForSubscribeNotificationEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $html = '<!DOCTYPE html><html><body style="padding-left:20%;padding-right:20%"><h1>Subscription notification</h1><p>Dear List Owner</p><p>User <strong>{EMAIL}</strong> has recently subscribed to your mail list <strong>{LIST_NAME}</strong></p></body></html>';

        // Avoid using Olequent Model in migration
        // What if the model gets changed or deleted completely later on?
        DB::insert('INSERT INTO '.table('layouts').' (uid, alias, group_name, type, subject, content) VALUES (?, ?, ?, ?, ?, ?)', [
            uniqid(),
            'subscribe_notification_for_list_owner',
            'Notification for List Owner',
            'email',
            'Subscription Notification',
            $html,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
