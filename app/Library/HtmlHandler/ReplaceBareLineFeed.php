<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;

class ReplaceBareLineFeed implements StageInterface
{
    public function __invoke($html)
    {
        return StringHelper::replaceBareLineFeed($html);
    }
}
