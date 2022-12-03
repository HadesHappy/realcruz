<?php

namespace Acelle\Library\HtmlHandler;

use League\Pipeline\StageInterface;
use Acelle\Library\StringHelper;
use Acelle\Model\Product;
use Exception;

class TransformWidgets implements StageInterface
{
    public function __invoke($html)
    {
        // transform product list widget
        $html = $this->transformProductListWidget($html);

        // transform product widget
        $html = $this->transformProductWidget($html);

        // transform rss widget
        $html = $this->transformRssWidget($html);

        return $html;
    }

    public function transformProductListWidget($html)
    {
        $_this = $this;
        return StringHelper::updateHtml($html, function ($dom) use ($_this) {
            $finder = new \DOMXPath($dom);
            $classname="product-list-widget";
            $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

            foreach ($nodes as $node) {
                $pList = $node->getElementsByTagName('products')[0];
                $pHtml =  Product::generateWidgetProductListHtmlContent([
                    'count' => $node->getAttribute('data-count'),
                    'cols' => $node->getAttribute('data-cols'),
                    'sort' => $node->getAttribute('data-sort'),
                    'preview' => $node->getAttribute('data-preview'),
                ]);

                if ($pList != null) {
                    $_this->update_html($dom, $pList, $pHtml);
                }
            }
        });
    }

    public function transformProductWidget($html)
    {
        $_this = $this;
        return StringHelper::updateHtml($html, function ($dom) use ($_this) {
            $finder = new \DOMXPath($dom);
            $classname="product-widget";
            $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

            foreach ($nodes as $node) {
                $html = $_this->get_inner_html($node);

                if ($node->getAttribute('data-id')) {
                    $html = Product::generateWidgetProductHtmlContent([
                        'id' => $node->getAttribute('data-id'),
                        'content' => $html,
                    ]);

                    $_this->update_html($dom, $node, $html);
                } else {
                    $_this->update_html($dom, $node, '<div class="alert alert-warning text-center">'.trans('messages.template.no_product_selected').'</div>');
                }
            }
        });
    }

    public function transformRssWidget($html)
    {
        $_this = $this;
        return StringHelper::updateHtml($html, function ($dom) use ($_this) {
            $finder = new \DOMXPath($dom);
            $classname="rss-widget";
            $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

            foreach ($nodes as $node) {
                try {
                    if (!$node->getAttribute('data-config')) {
                        throw new \Exception(trans('messages.rss.no_options'));
                    }

                    $config = json_decode(base64_decode($node->getAttribute('data-config')), true);

                    if (!isset($config['url']) || !$config['url']) {
                        throw new \Exception(trans('messages.rss.url_required'));
                    }

                    $html = parseRss($config);
                    $_this->update_html($dom, $node, $html);
                } catch (\Exception $e) {
                    $_this->update_html($dom, $node, '<div class="alert alert-warning text-center">'.$e->getMessage().'</div>');
                }
            }
        });
    }

    public function get_inner_html($node)
    {
        $innerHTML= '';
        $children = $node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $child->ownerDocument->saveXML($child);
        }

        return $innerHTML;
    }

    public function update_html($dom, $node, $html)
    {
        // remove all child
        while ($node->hasChildNodes()) {
            $node->removeChild($node->childNodes->item(0));
        }

        if (empty(trim($html))) {
            return;
        }

        // try to append html
        $tmpDoc = new \DOMDocument();
        $tmpDoc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NODEFDTD);
        foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $cNode) {
            $n = $dom->importNode($cNode, true);
            $node->appendChild($n);
        }
    }
}
