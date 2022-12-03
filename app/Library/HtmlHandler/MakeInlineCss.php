<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;

class MakeInlineCss implements StageInterface
{
    public $cssFiles;

    public function __construct(array $cssFiles)
    {
        $this->cssFiles = $cssFiles;
    }

    public function __invoke($html)
    {
        return makeInlineCss($html, $this->cssFiles);
    }
}
