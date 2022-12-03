<?php

namespace Acelle\Jobs;

class UpdateUserJob extends Base
{
    protected $customer;

    public $timeout = 120;

    public function __construct($customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->customer->updateCache();
    }
}
