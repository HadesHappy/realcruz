<?php

namespace Acelle\Mail;

use Acelle\Model\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SettingMailerTest extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('messages.setting.mailer.test.email_subject'))
            ->view('emails.SettingMailerTest');
    }
}
