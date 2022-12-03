<?php

namespace Acelle\Library\Traits;

use Acelle\Library\QuotaManager;
use Carbon\Carbon;

trait HasQuota
{
    public function getQuotaManager($name)
    {
        $quota = new QuotaManager($this, $name);
        return $quota;
    }
    public function addCredits($name, $count)
    {
        return $this->getQuotaManager($name)->addCredits($count);
    }

    public function setCredits($name, $count)
    {
        return $this->getQuotaManager($name)->setCredits($count);
    }

    public function getCreditsUsed(string $name, Carbon $from = null, Carbon $to = null)
    {
        return $this->getQuotaManager($name)->getCreditsUsed($from, $to);
    }

    public function cleanupCreditsStorageFiles($name)
    {
        return $this->getQuotaManager($name)->cleanup();
    }

    public function getRemainingCredits($name)
    {
        return $this->getQuotaManager($name)->getRemainingCredits();
    }

    public function updateRemainingCredits($name)
    {
        return $this->getQuotaManager($name)->updateRemainingCredits();
    }
}
