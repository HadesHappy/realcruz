<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerificationFieldsToSendingDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('sending_domains', function (Blueprint $table) {
                $table->text('verification_token')->nullable();
                $table->boolean('domain_verified')->default(false);
                $table->boolean('dkim_verified')->default(false);
                $table->boolean('spf_verified')->default(false);
            });
        } catch (\Exception $ex) {
            //
        }

        try {
            $domains = \Acelle\Model\SendingDomain::whereNull('verification_token')->get();
            foreach ($domains as $domain) {
                $domain->generateVerificationToken();
                $domain->save();
            }
        } catch (\Exception $ex) {
            //
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
            $table->dropColumn('verification_token');
            $table->dropColumn('domain_verified');
            $table->dropColumn('dkim_verified');
            $table->dropColumn('spf_verified');
        });
    }
}
