<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSubscribersVerificationStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement(sprintf('UPDATE %s s JOIN %s v ON s.id = v.subscriber_id SET s.verification_status = v.result, s.last_verification_by = v.email_verification_server_id, s.last_verification_at = NOW() WHERE s.verification_status IS NULL;', table('subscribers'), table('email_verifications')));
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
