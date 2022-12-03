<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultTemplateForSenderVerificationEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $html = '<!DOCTYPE html><html><body style="padding-left:20%;padding-right:20%"><h1>Identify Verification</h1><p>Dear {USER_NAME}</p><p>Thank you for registering your email ({USER_EMAIL}) with us. Please click the link below to verify your email address against our service. If you do not request this verification, please just ignore this email</p><p>{VERIFICATION_LINK}</p></body></html>';


        // Avoid using Olequent Model in migration
        // What if the model gets changed or deleted completely later on?
        DB::insert('INSERT INTO '.table('layouts').' (uid, alias, group_name, type, subject, content) VALUES (?, ?, ?, ?, ?, ?)', [
            uniqid(),
            'sender_verification_email',
            'Verification',
            'email',
            'Verify your sender identity',
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
        Schema::table('layouts', function (Blueprint $table) {
            //
        });
    }
}
