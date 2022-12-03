<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Soundasleep\Html2Text;

class HtmlToPlain implements StageInterface
{
    public function __invoke($html)
    {
        $options = [ 'ignore_errors' => true ];
        $plain = Html2Text::convert($html, $options);
        return $plain;
    }
}
