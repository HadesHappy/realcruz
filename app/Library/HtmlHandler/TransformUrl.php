<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;
use Acelle\Model\TrackingDomain;
use Acelle\Model\Template;
use Exception;

class TransformUrl implements StageInterface
{
    public $template;
    public $msgId;
    public $domain;

    public function __construct(Template $template, $msgId, TrackingDomain $domain = null)
    {
        $this->template = $template;
        $this->msgId = $msgId;
        $this->domain = $domain;
    }

    public function __invoke($html)
    {
        // Convert a normal link to (click) trackable link
        $transformClosure = function ($url, $element) {
            if (!parse_url($url, PHP_URL_HOST)) {
                throw new Exception("TransformUrl only works with public URLs. i.e. URLs with http:// or https:// or //");
            }

            // Transform LINKS only, to track click
            // Do not transform IMG or other sources
            if (strtolower($element->nodeName) != 'a') {
                return $url;
            }

            // Also, check if it is the currnet host URL, just ignore
            if (strpos($url, url('')) === 0) {
                return $url;
            }

            // Also, check if it is the currnet tracking URL, just ignore
            if ($this->domain && strpos($url, $this->domain->getUrl()) === 0) {
                return $url;
            }

            return StringHelper::makeTrackableLink($url, $this->msgId);
        };

        $html = $this->template->getContentWithTransformedAssetsUrls($html, $withHost = true, $transformClosure, $this->domain);
        return $html;
    }
}
