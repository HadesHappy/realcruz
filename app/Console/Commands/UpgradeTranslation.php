<?php

namespace Acelle\Console\Commands;

use Illuminate\Console\Command;
use Acelle\Library\UpgradeManager;
use Acelle\Model\Language;

class UpgradeTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update translation files to make those up-to-date with the default EN language';

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
        \Acelle\Helpers\pcopy(resource_path('lang/en'), resource_path('lang/default'));
        Language::dump();
    }
}
