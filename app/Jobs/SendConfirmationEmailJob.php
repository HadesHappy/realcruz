<?php

namespace Acelle\Jobs;

use Acelle\Library\Log as MailLog;

class SendConfirmationEmailJob extends Base
{
    protected $subscribers;
    protected $mailList;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subscribers, $mailList)
    {
        $this->subscribers = $subscribers;
        $this->mailList = $mailList;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        MailLog::info(sprintf('Start re-sending confirmation email to %s contacts', sizeof($this->subscribers)));
        foreach ($this->subscribers as $subscriber) {
            try {
                MailLog::info(sprintf('Re-sending confirmation email to %s (%s)', $subscriber->email, $subscriber->id));
                $this->mailList->sendSubscriptionConfirmationEmail($subscriber);
            } catch (\Exception $e) {
                MailLog::error(sprintf('Something went wrong when re-sending confirmation email for mail list %s. Error: %s', $this->mailList->name, $e->getMessage()));
                break;
            }
        }
        MailLog::info('Finish re-sending confirmation email');
    }
}
