<?php

namespace Acelle\Library\Storage\Contracts;

interface StorageService
{
    public function store(Storable $object);
}
