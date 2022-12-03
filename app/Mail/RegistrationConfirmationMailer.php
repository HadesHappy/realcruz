<?php

namespace Acelle\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationConfirmationMailer extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected $content;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $subject)
    {
        $this->content = $content;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->from(config('mail.from')['address'], config('mail.from')['name'])
                    ->view('users.registration_confirmation_email')
                    ->with(['content' => $this->content]);
    }
}
