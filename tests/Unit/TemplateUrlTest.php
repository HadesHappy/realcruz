<?php

namespace Tests\Unit;

use Tests\TestCase;
use Acelle\Model\Template;
use Acelle\Model\TrackingDomain;
use Acelle\Library\StringHelper;
use DOMDocument;
use DomXpath;
use Exception;

class TemplateUrlTest extends TestCase
{
    public const RELATIVE = 'images/sample.png';
    public const ABSOLUTE = '/fake/sample.png';
    public const PUBLIC = 'http://cdn.example.com/sample.png';
    public const CDN = 'http://cdn.example.com/sample.png';
    public const EMPTY = '';
    public const SHARP = '#';

    public const HOST = 'http://localhost/';
    public const TRACKING_DOMAIN = 'track.example.com';
    public const TRACKING_HOST = 'http://track.example.com/';

    public function initTemplate()
    {
        $template = new Template();
        $template->generateUid();
        $template->content = strtr("
            <html>
            <body>
                <img name='relative' src='%relative'>
                <a name='absolute' href='%absolute'>Click me</a>
                <img name='public' src='%public'>
                <a name='cdn' href='%cdn'>Click me</a>
                <img name='empty' src='%empty'>
                <img name='sharp' src='%sharp'>
            </body>
            </html>
        ", [
            '%relative' => self::RELATIVE,
            '%absolute' => self::ABSOLUTE,
            '%public' => self::PUBLIC,
            '%cdn' => self::CDN,
            '%empty' => self::EMPTY,
            '%sharp' => self::SHARP,
        ]);
        return $template;
    }

    public function initTrackingDomain()
    {
        $domain = new TrackingDomain();
        $domain->name = self::TRACKING_DOMAIN;
        $domain->scheme = 'http';
        return $domain;
    }

    public function getDOMDocument($html)
    {
        $document = new DOMDocument();
        $document->encoding = 'utf-8';
        $document->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NODEFDTD);

        return $document;
    }

    public function getElementByAttribute($html, $attribute, $value)
    {
        $document = $this->getDOMDocument($html);
        $xpath = new DomXpath($document);
        $elements = $xpath->query("//*[@{$attribute}='$value']");
        if (empty($elements)) {
            throw new Exception("Cannot find element with '$attribute'='$value'");
        }

        if (sizeof($elements) > 1) {
            throw new Exception("There are more than one elements with '$attribute'='$value'");
        }

        return $elements[0];
    }

    public function getElementAttributes($html)
    {
        $map = [
            'img' => 'src',
            'link' => 'href',
            'a' => 'href',
        ];
        $out = [];
        $names = [ 'relative', 'absolute', 'public', 'cdn', 'empty', 'sharp' ];
        foreach ($names as $name) {
            $element = $this->getElementByAttribute($html, 'name', $name);
            $out[$name] = $element->getAttribute($map[$element->nodeName]);
        }

        return $out;
    }

    public function test_transform_assets_urls_without_parameters()
    {
        $template = $this->initTemplate();
        $html = $template->getContentWithTransformedAssetsUrls($template->content);

        $out = $this->getElementAttributes($html);

        // Transformed
        $this->assertMatchesRegularExpression('/^\/assets\/.*sample.png$/', $out['relative']);

        // Unchanged
        $this->assertEquals($out['absolute'], self::ABSOLUTE); // unchanged
        $this->assertEquals($out['public'], self::PUBLIC); // unchanged
        $this->assertEquals($out['cdn'], self::CDN); // unchanged
        $this->assertEquals($out['empty'], self::EMPTY); // unchanged
        $this->assertEquals($out['sharp'], self::SHARP); // unchanged
    }

    public function test_transform_assets_urls_with_host()
    {
        $template = $this->initTemplate();
        $html = $template->getContentWithTransformedAssetsUrls($template->content, $host = true);

        $out = $this->getElementAttributes($html);

        // Transformed
        $this->assertMatchesRegularExpression('/^http.*\/assets\/.*sample.png$/', $out['relative']);
        $this->assertTrue(strpos($out['relative'], self::HOST) === 0); // contains "http:://localhost..."

        // Unchanged
        $this->assertEquals($out['absolute'], join_url(self::HOST, self::ABSOLUTE));
        $this->assertEquals($out['public'], self::PUBLIC); // unchanged
        $this->assertEquals($out['cdn'], self::CDN); // unchanged
        $this->assertEquals($out['empty'], self::EMPTY); // unchanged
        $this->assertEquals($out['sharp'], self::SHARP); // unchanged
    }

    public function test_transform_assets_urls_with_host_and_transform_closure()
    {
        // IMPORTANT: only affect <A> tag
        $template = $this->initTemplate();
        $msgId = 'TEST';
        $transform = function ($url, $element) use ($msgId) {
            if ($element->nodeName == 'a') {
                return StringHelper::makeTrackableLink($url, $msgId);
            } else {
                return $url;
            }
        };

        $html = $template->getContentWithTransformedAssetsUrls($template->content, $host = true, $transform, $domain = null);

        $out = $this->getElementAttributes($html);

        // Transformed
        // $this->assertMatchesRegularExpression( '/^http.*\/assets\/.*sample.png$/', $out['relative']);
        $this->assertTrue(strpos($out['relative'], self::HOST) === 0); // contains "http:://localhost..."

        // Unchanged
        $this->assertEquals($out['public'], self::PUBLIC); // unchanged
        $this->assertEquals($out['empty'], self::EMPTY); // unchanged
        $this->assertEquals($out['sharp'], self::SHARP); // unchanged
    }

    public function test_transform_assets_urls_with_host_and_tracking_domain()
    {
        $template = $this->initTemplate();
        $domain = $this->initTrackingDomain();
        $html = $template->getContentWithTransformedAssetsUrls($template->content, $host = true, $transform = null, $domain);

        $out = $this->getElementAttributes($html);

        // Transformed
        $this->assertMatchesRegularExpression('/^http.*'.$domain->name.'\/[^\/]+$/', $out['relative']);
        $this->assertTrue(strpos($out['relative'], $domain->getUrl()) === 0);
        $this->assertEquals($out['absolute'], join_url($domain->getUrl(), StringHelper::base64UrlEncode(join_url(self::HOST, self::ABSOLUTE))));
        $this->assertEquals($out['public'], join_url($domain->getUrl(), StringHelper::base64UrlEncode(self::PUBLIC)));
        $this->assertEquals($out['cdn'], join_url($domain->getUrl(), StringHelper::base64UrlEncode(self::CDN)));

        // Unchanged
        // $this->assertEquals( $out['absolute'], join_url(self::HOST, self::ABSOLUTE) );
        // $this->assertEquals( $out['public'], self::PUBLIC ); // unchanged
        // $this->assertEquals( $out['cdn'], self::CDN ); // unchanged

        // Unchange
        $this->assertEquals($out['empty'], self::EMPTY); // unchanged
        $this->assertEquals($out['sharp'], self::SHARP); // unchanged
    }
}
