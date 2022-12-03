<?php

namespace Acelle\Library\Contracts;

interface HasQuota
{
    public function getQuotaSettings(string $name): ?array;
    public function getUid(): ?string;
}
