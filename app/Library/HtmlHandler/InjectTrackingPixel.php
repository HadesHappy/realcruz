<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;

class InjectTrackingPixel implements StageInterface
{
    public $campaign;
    public $msgId;

    public function __construct($campaign, $msgId)
    {
        $this->campaign = $campaign;
        $this->msgId = $msgId;
    }

    public function __invoke($html)
    {
        $pixel = $this->campaign->makeTrackingPixel($this->msgId);
        return StringHelper::appendHtml($html, $pixel);
    }
}
