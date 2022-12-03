<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Acelle\Library\Log;
use Acelle\Library\StringHelper;
use Acelle\Library\ExtendedSwiftMessage;
use Acelle\Model\Campaign;
use Acelle\Model\User;
use Acelle\Model\MailList;
use Acelle\Model\Subscriber;
use Acelle\Model\TrackingLog;
use Acelle\Model\SendingServer;
use Acelle\Model\AutoTrigger;
use Acelle\Model\SendingServerElasticEmailApi;
use Acelle\Model\SendingServerElasticEmail;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Validator;

class TestCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->testImap();
        return 0;
    }

    public function testSmtp()
    {
        $transport = new \Swift_SmtpTransport('smtp.elasticemail.com', 2525, 'tls');
        $transport->setUsername('');
        $transport->setPassword('');
        ;

        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($transport);

        // Create a message
        $message = new ExtendedSwiftMessage('Wonderful Subject');
        $message->setFrom(array('' => 'Asish'));
        $message->setTo(array('' => 'Louis'));
        $message->setBody('Here is the message itself');

        // Send the message
        $mailer->send($message);
    }

    public function testImap()
    {
        // Connect to IMAP server
        $imapPath = "{mail.example.com:993/imap/tls}INBOX";

        // try to connect
        $inbox = imap_open($imapPath, 'user@example.com', 'password');

        // search and get unseen emails, function will return email ids
        $emails = imap_search($inbox, 'UNSEEN');

        if (!empty($emails)) {
            foreach ($emails as $message) {
                var_dump($message);
            }
        }

        // colse the connection
        imap_expunge($inbox);
        imap_close($inbox);
    }
}
