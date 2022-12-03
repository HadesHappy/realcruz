<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;

class AddDoctype implements StageInterface
{
    public function __invoke($html)
    {
        $closure = function () {
        };

        // Call StringHelper::updateHtml in order to have DOCTYPE available
        return StringHelper::updateHtml($html, $closure);
    }
}
