<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Acelle\Model\SendingDomain;

class UpdateSendingDomains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update schema
        Schema::table('sending_domains', function (Blueprint $table) {
            $table->dropColumn('options');
            $table->dropColumn('dkim_selector');
            $table->dropColumn('verification_hostname');
            $table->dropColumn('domain_verified');
            $table->dropColumn('dkim_verified');
            $table->dropColumn('spf_verified');
        });

        // Delete inactive domains
        SendingDomain::inactive()->delete();

        // Dummy data for sending domains so they do not crash
        $fake = [
            'identity' => [
                'type' => 'TXT',
                'name' => 'N/A',
                'value' => 'N/A',
            ],
            'dkim' => [
                [
                    'type' => 'TXT',
                    'name' => 'N/A',
                    'value' => 'N/A',
                ]
            ],
            'results' => [
                'identity' => true,
                'dkim' => true,
            ]
        ];

        foreach (SendingDomain::active()->get() as $domain) {
            $domain->updateVerificationTokens($fake);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sending_domains', function (Blueprint $table) {
            //
        });
    }
}
