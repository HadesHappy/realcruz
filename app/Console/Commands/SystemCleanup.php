<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Acelle\Model\Log;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log as LaravelLog;

class SystemCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'System cleanup';

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
        /*
        // Delete old log
        Log::where('created_at', '<', new Carbon('1 year ago'))->delete();

        // Delete orphan subscription
        $query = Subscription::leftJoin('customers', 'subscriptions.customer_id', '=', 'customers.id')->whereNull('customers.id');
        if ($query->count()) {
            LaravelLog::warning('Orphan subscriptions');
            $query->delete();
        }
        */
        return 0;
    }
}
