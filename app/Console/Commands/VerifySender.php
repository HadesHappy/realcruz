<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Acelle\Model\Sender;

class VerifySender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sender:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify Sender';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $senders = Sender::pending()->get();
        foreach ($senders as $sender) {
            $sender->updateVerificationStatus();
        }
        return 0;
    }
}
