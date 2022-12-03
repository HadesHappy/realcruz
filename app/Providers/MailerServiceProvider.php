<?php

namespace Acelle\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Acelle\Model\Setting;
use Acelle\Model\SendingServer;
use Acelle\Model\SendingServerSmtp;
use Acelle\Model\SendingServerSendmail;

class MailerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('xmailer', function ($app) {
            $mailer = Setting::get('mailer.mailer') ?: Setting::get('mailer.driver');

            switch ($mailer) {
                case SendingServer::TYPE_SMTP:
                    $server = SendingServerSmtp::instantiateFromSettings([
                        'host' => Setting::get('mailer.host') ?? config('mail.host'),
                        'smtp_port' => Setting::get('mailer.port') ?? config('mail.port'),
                        'smtp_protocol' => Setting::get('mailer.encryption') ?? config('mail.encryption'),
                        'smtp_username' => Setting::get('mailer.username') ?? config('mail.username'),
                        'smtp_password' => Setting::get('mailer.password') ?? config('mail.password'),
                        'from_name' => Setting::get('mailer.from.name') ?? config('mail.from.name'),
                        'from_address' => Setting::get('mailer.from.address') ?? config('mail.from.address'),
                    ]);

                    break;

                case SendingServer::TYPE_SENDMAIL:
                    $server = SendingServerSendmail::instantiateFromSettings([
                        'sendmail_path' => Setting::get('mailer.sendmail_path') ?? config('mail.sendmail'),
                        'from_name' => Setting::get('mailer.from.name') ?? config('mail.from.name'),
                        'from_address' => Setting::get('mailer.from.address') ?? config('mail.from.address'),
                    ]);
                    break;
                default:
                    throw new \Exception("Mail mailer '{$mailer}' not found", 1);
                    break;
            }

            return $server;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['xmailer'];
    }
}
