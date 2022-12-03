<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTemplateForUnsubscribeNotificationEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $html = '<!DOCTYPE html><html><body style="padding-left:20%;padding-right:20%"><h1>Unsubscribe notification</h1><p>Dear List Owner</p><p>User <strong>{EMAIL}</strong> has unsubscribed from your mail list <strong>{LIST_NAME}</strong></p></body></html>';

        // Avoid using Olequent Model in migration
        // What if the model gets changed or deleted completely later on?
        DB::insert('INSERT INTO '.table('layouts').' (uid, alias, group_name, type, subject, content) VALUES (?, ?, ?, ?, ?, ?)', [
            uniqid(),
            'unsubscribe_notification_for_list_owner',
            'Notification for List Owner',
            'email',
            'Unsubscribe notification',
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
