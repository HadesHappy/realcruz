<?php

namespace Tests\Unit;

use Tests\TestCase;
use League\Pipeline\PipelineBuilder;
use Acelle\Library\HtmlHandler\ParseRss;
use Acelle\Library\HtmlHandler\ReplaceBareLineFeed;
use Acelle\Library\HtmlHandler\AppendHtml;
use Acelle\Library\HtmlHandler\TransformTag;
use Acelle\Library\HtmlHandler\InjectTrackingPixel;
use Acelle\Library\HtmlHandler\MakeInlineCss;
use Acelle\Library\HtmlHandler\TransformUrl;
use Acelle\Library\HtmlHandler\AddDoctype;
use Acelle\Library\HtmlHandler\RemoveTitleTag;
use Acelle\Model\Subscriber;
use Acelle\Model\Campaign;
use Acelle\Model\MailList;
use Acelle\Model\Template;
use Acelle\Model\TrackingDomain;
use Mockery;
use Exception;
use DOMDocument;
use Acelle\Library\StringHelper;

class HtmlHandlersTest extends TestCase
{
    public function test_parse_rss()
    {
        $pipeline = new PipelineBuilder();
        $pipeline->add(new ParseRss());
        $html = "{% set rss = rss('http://rss.cnn.com/rss/edition.rss', 5) %}
            {% for post in rss.item %}
                <h3>{{ post.title }}</h3>
                <p>{{ post.description | raw }}</p>
                <hr>
            {% endfor %}";

        $out = $pipeline->build()->process($html);

        $this->assertTrue(strpos($out, '{% for post in rss.item %}') === false);
        $this->assertTrue(strpos($out, '<h3>{{ post.title }}</h3>') === false);
    }

    public function test_replace_bare_line_feed()
    {
        $pipeline = new PipelineBuilder();
        $pipeline->add(new ReplaceBareLineFeed());
        $html = "Hello\nWorld\nFrom\r\nLaravel\n";
        $out = $pipeline->build()->process($html);

        $this->assertEquals($out, "Hello\r\nWorld\r\nFrom\r\nLaravel");
    }

    public function test_append_html()
    {
        $pipeline = new PipelineBuilder();
        $pipeline->add(new AppendHtml('<a>click me</a>'));
        $pipeline->add(new AppendHtml('<p>read me</p>'));

        $html = "<html><body><div>Content main</div></body></html>";
        $out = $pipeline->build()->process($html);

        $this->assertEquals($out, '<!DOCTYPE html><html><body><div>Content main</div><a>click me</a><p>read me</p></body></html>');
    }

    public function test_transform_tag()
    {
        // List
        $list = Mockery::mock(new MailList());

        // Campaign
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));
        $campaign->defaultMailList = $list;
        $campaign->shouldReceive('isStdClassSubscriber')->andReturn(true);

        // Subscriber
        $subscriber = Mockery::mock(new Subscriber());
        $subscriber->uid = '000000';

        // Pipe
        $pipeline = new PipelineBuilder();
        $pipeline->add(new TransformTag($campaign, $subscriber, $msgId = 'TEST'));
        $pipeline->add(new AppendHtml('<a>click me</a>'));
        $pipeline->add(new AppendHtml('<p>read me</p>'));

        $html = "<html><body><div>Campaign: '{CAMPAIGN_NAME}' Subscriber: '{SUBSCRIBER_UID}'</div></body></html>";
        $out = $pipeline->build()->process($html);

        $this->assertEquals($out, "<!DOCTYPE html><html><body><div>Campaign: 'Campaign' Subscriber: '%UID%'</div><a>click me</a><p>read me</p></body></html>");
    }

    public function test_inject_tracking_pixel()
    {
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));

        $pipeline = new PipelineBuilder();
        $pipeline->add(new InjectTrackingPixel('MSGID'));

        $html = "<html><body><div>Content main</div></body></html>";
        $out = $pipeline->build()->process($html);

        $this->assertEquals($out, '<!DOCTYPE html><html><body><div>Content main</div><img src="http://localhost/p/TVNHSUQ/open" width="0" height="0" alt="" style="visibility:hidden"></body></html>');
    }

    public function test_make_inline_css()
    {
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));

        $pipeline = new PipelineBuilder();
        $pipeline->add(new MakeInlineCss([storage_path('tests/sample.css')]));

        $html = "<html><body><div class='main'><a>Content main</a><span class='big blue '>Test<span></div></body></html>";
        $out = $pipeline->build()->process($html);

        $this->assertEquals($out, '<!DOCTYPE html><html><body><div class="main"><a style="color:green">Content main</a><span class="big blue " style="font-weight:600;color:blue">Test<span></span></span></div></body></html>');
    }

    public function test_test_campaign_html_content()
    {
        // Template
        $template = Mockery::mock(new Template(['name' => 'Template']));
        $template->generateUid();
        $msgId = 'TEST';

        // List
        $list = Mockery::mock(new MailList());

        // Campaign
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));
        $campaign->defaultMailList = $list;
        $campaign->shouldReceive('isStdClassSubscriber')->andReturn(true);

        // Subscriber
        $subscriber = Mockery::mock(new Subscriber());
        $subscriber->uid = '000000';

        $pipeline = new PipelineBuilder();
        $pipeline->add(new AddDoctype());
        $pipeline->add(new RemoveTitleTag());
        $pipeline->add(new ReplaceBareLineFeed());
        $pipeline->add(new AppendHtml('<div>Hello world éèéêôâ</div>'));
        $pipeline->add(new ParseRss());
        $pipeline->add(new TransformTag($campaign, $subscriber, 'MSGID', $server = null));
        $pipeline->add(new TransformUrl($template, 'MSGID', $trackingDomain = null));
        $pipeline->add(new MakeInlineCss([storage_path('tests/sample.css')]));
        $pipeline->add(new InjectTrackingPixel('MSGID'));

        $html = $pipeline->build()->process("<title class='blue'>\nThisis the template title</title><a class=' big blue' href='https://mailchimp.com'></a><div>Campaign: '{CAMPAIGN_NAME}' Subscriber: '{SUBSCRIBER_UID}'</div>");

        $this->assertTrue(strpos($html, '<!DOCTYPE html>') === 0);
        $this->assertTrue(strpos($html, '<title>') === false);
        $this->assertTrue(strpos($html, "\n") === false);
        $this->assertTrue(strpos($html, '<div>Hello world éèéêôâ</div>') !== false);
        $this->assertTrue(strpos($html, "'%UID%'") !== false); // isStdClassSubscriber is always TRUE in test
    }

    public function test_test_campaign_html_with_types_of_urls()
    {
        // Template
        $template = Mockery::mock(new Template(['name' => 'Template']));
        $template->generateUid();
        $msgId = 'TEST';

        // List
        $list = Mockery::mock(new MailList());

        // Campaign
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));
        $campaign->defaultMailList = $list;
        $campaign->shouldReceive('isStdClassSubscriber')->andReturn(true);

        // Subscriber
        $subscriber = Mockery::mock(new Subscriber());
        $subscriber->uid = '000000';

        $pipeline = new PipelineBuilder();
        $pipeline->add(new AddDoctype());
        $pipeline->add(new RemoveTitleTag());
        $pipeline->add(new ReplaceBareLineFeed());
        $pipeline->add(new AppendHtml('<div>test</div>'));
        $pipeline->add(new ParseRss());
        $pipeline->add(new TransformTag($campaign, $subscriber, 'MSGID', $server = null));
        $pipeline->add(new TransformUrl($template, 'MSGID', $trackingDomain = null));
        $pipeline->add(new MakeInlineCss([storage_path('tests/sample.css')]));
        $pipeline->add(new InjectTrackingPixel('MSGID'));

        $html = $pipeline->build()->process("<a href='image/hehe'>Relative</a><img src='//cdn.fake.com/image/hehe'><a href='/absolute/url'>Absolute</a><a href='//absolute/url'>Two slashes</a><a href='mailto:hello@example.com'>hello@example.com</a><a href='#sectionN'>Scroll to Section N</a>");

        $this->assertTrue(strpos($html, '<!DOCTYPE html>') === 0);

        // mailto:hello@example.com should not be modified
        $this->assertTrue(strpos($html, '<a href="mailto:hello@example.com">hello@example.com</a>') !== false);
        $this->assertTrue(strpos($html, '<a href="#sectionN">Scroll to Section N</a>') !== false);
    }

    public function test_test_campaign_html_content_with_tracking_domain()
    {
        // Tracking domain
        $domain = new TrackingDomain();
        $domain->name = 'track.example.com';
        $domain->scheme = 'https';

        // Template
        $html = "<html><body><a href='/hello/world'></body></html>";
        $template = Mockery::mock(new Template(['name' => 'Template']));
        $template->generateUid();

        // List
        $list = Mockery::mock(new MailList());

        // Campaign
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));
        $campaign->defaultMailList = $list;
        $campaign->shouldReceive('isStdClassSubscriber')->andReturn(true);

        // Subscriber
        $subscriber = Mockery::mock(new Subscriber());
        $subscriber->uid = '000000';

        $pipeline = new PipelineBuilder();
        $pipeline->add(new AddDoctype());
        $pipeline->add(new RemoveTitleTag());
        $pipeline->add(new ReplaceBareLineFeed());
        $pipeline->add(new AppendHtml('<div>Hello world éèéêôâ</div>'));
        $pipeline->add(new ParseRss());
        $pipeline->add(new TransformTag($campaign, $subscriber, 'MSGID', $server = null));

        $pipeline->add(new TransformUrl($template, 'MSGID', $domain));
        $pipeline->add(new MakeInlineCss([storage_path('tests/sample.css')]));
        $pipeline->add(new InjectTrackingPixel('MSGID'));

        $html = $pipeline->build()->process("<title class='blue'>\nThisis the template title</title><a class=' big blue' href='https://mailchimp.com'></a><div>Campaign: '{CAMPAIGN_NAME}' Subscriber: '{SUBSCRIBER_UID}'</div>");

        // Examine the output with DOM
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $a = $dom->getElementsByTagName('a')[0];
        $url = $a->getAttribute('href');

        // At first, URL is a tracking URL
        $encoded = "aHR0cDovL2xvY2FsaG9zdC9wL2FIUjBjSE02THk5dFlXbHNZMmhwYlhBdVkyOXQvY2xpY2svVFZOSFNVUQ";
        $this->assertEquals($url, "{$domain->scheme}://{$domain->name}/{$encoded}");

        // When decoded, it is a click trackable link
        $this->assertEquals(StringHelper::base64UrlDecode($encoded), url('p/aHR0cHM6Ly9tYWlsY2hpbXAuY29t/click/TVNHSUQ'));

        // and resolving it to the original Mailchimp link
        $this->assertEquals(StringHelper::base64UrlDecode('aHR0cHM6Ly9tYWlsY2hpbXAuY29t'), 'https://mailchimp.com');

        // Other assertinos
        $this->assertTrue(strpos($html, '<!DOCTYPE html>') === 0);
        $this->assertTrue(strpos($html, '<title>') === false);
        $this->assertTrue(strpos($html, "\n") === false);
        $this->assertTrue(strpos($html, '<div>Hello world éèéêôâ</div>') !== false);
        $this->assertTrue(strpos($html, "'%UID%'") !== false);
    }

    public function test_test_campaign_with_special_tags()
    {
        // Tracking domain
        $domain = new TrackingDomain();
        $domain->name = 'track.example.com';
        $domain->scheme = 'https';

        // Mail list
        $list = new MailList();
        $list->generateUid();

        // Template
        $template = Mockery::mock(new Template(['name' => 'Template']));
        $template->generateUid();

        // List
        $list = Mockery::mock(new MailList());
        $list->generateUid();

        // Campaign
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));
        $campaign->defaultMailList = $list;
        $campaign->trackingDomain = $domain;

        // Subscriber
        $subscriber = Mockery::mock(new Subscriber());
        $subscriber->mailList = $list;
        $subscriber->uid = '000000';

        $pipeline = new PipelineBuilder();
        $pipeline->add(new AddDoctype());
        $pipeline->add(new RemoveTitleTag());
        $pipeline->add(new ReplaceBareLineFeed());
        $pipeline->add(new AppendHtml('<div>Hello world éèéêôâ</div>'));
        $pipeline->add(new ParseRss());
        $pipeline->add(new TransformTag($campaign, $subscriber, 'MSGID', $server = null));
        $pipeline->add(new InjectTrackingPixel('MSGID'));
        $pipeline->add(new TransformUrl($template, 'MSGID', $domain)); // click url, tracking domain applied
        $pipeline->add(new MakeInlineCss([storage_path('tests/sample.css')]));


        $html = $pipeline->build()->process("<title class='blue'>\nThisis the template title</title><a class=' big blue' href='https://mailchimp.com'></a><div>Campaign: '{CAMPAIGN_NAME}' Subscriber: '{SUBSCRIBER_UID}'</div><a name='unsubscribe' href='{UNSUBSCRIBE_URL}'>Unsubscribe</a>|{UPDATE_PROFILE_URL}|{WEB_VIEW_URL}");

        // Examine the output with DOM
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $a = $dom->getElementsByTagName('a')[0];
        $url = $a->getAttribute('href');

        // At first, URL is a tracking URL
        $encoded = "aHR0cDovL2xvY2FsaG9zdC9wL2FIUjBjSE02THk5dFlXbHNZMmhwYlhBdVkyOXQvY2xpY2svVFZOSFNVUQ";
        $this->assertEquals($url, "{$domain->scheme}://{$domain->name}/{$encoded}");

        // When decoded, it is a click trackable link
        $this->assertEquals(StringHelper::base64UrlDecode($encoded), url('p/aHR0cHM6Ly9tYWlsY2hpbXAuY29t/click/TVNHSUQ'));

        // and resolving it to the original Mailchimp link
        $this->assertEquals(StringHelper::base64UrlDecode('aHR0cHM6Ly9tYWlsY2hpbXAuY29t'), 'https://mailchimp.com');

        // Other assertinos
        $this->assertTrue(strpos($html, '<!DOCTYPE html>') === 0);
        $this->assertTrue(strpos($html, '<title>') === false);
        $this->assertTrue(strpos($html, "\n") === false);
        $this->assertTrue(strpos($html, '<div>Hello world éèéêôâ</div>') !== false);
        $this->assertTrue(strpos($html, "'000000'") !== false);

        // Special tags
        //$this->assertTrue(strpos($html, 'c/000000/unsubscribe/TVNHSUQ') !== false);
        //$this->assertTrue(strpos($html, 'update-profile') !== false);
        //$this->assertTrue(strpos($html, 'campaigns/TVNHSUQ/web-view') !== false);
    }

    public function test_test_campaign_with_special_tags_without_tracking_domain()
    {
        // Mail list
        $list = new MailList();
        $list->generateUid();

        // Template
        $template = Mockery::mock(new Template(['name' => 'Template']));
        $template->generateUid();

        // List
        $list = Mockery::mock(new MailList());
        $list->generateUid();

        // Campaign
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));
        $campaign->defaultMailList = $list;

        // Subscriber
        $subscriber = Mockery::mock(new Subscriber());
        $subscriber->mailList = $list;
        $subscriber->uid = '000000';

        $pipeline = new PipelineBuilder();
        $pipeline->add(new AddDoctype());
        $pipeline->add(new RemoveTitleTag());
        $pipeline->add(new ReplaceBareLineFeed());
        $pipeline->add(new AppendHtml('<div>Hello world éèéêôâ</div>'));
        $pipeline->add(new ParseRss());
        $pipeline->add(new TransformTag($campaign, $subscriber, 'MSGID', $server = null));
        $pipeline->add(new InjectTrackingPixel('MSGID'));
        $pipeline->add(new TransformUrl($template, 'MSGID', null)); // click url, tracking domain applied
        $pipeline->add(new MakeInlineCss([storage_path('tests/sample.css')]));


        $html = $pipeline->build()->process("<title class='blue'>\nThisis the template title</title><a class=' big blue' href='https://mailchimp.com'></a><div>Campaign: '{CAMPAIGN_NAME}' Subscriber: '{SUBSCRIBER_UID}'</div><a name='unsubscribe' href='{UNSUBSCRIBE_URL}'>Unsubscribe</a>|{UPDATE_PROFILE_URL}|{WEB_VIEW_URL}");

        // Other assertinos
        $this->assertTrue(strpos($html, "'000000'") !== false);
        $this->assertTrue(strpos($html, '<!DOCTYPE html>') === 0);
        $this->assertTrue(strpos($html, '<title>') === false);
        $this->assertTrue(strpos($html, "\n") === false);
        $this->assertTrue(strpos($html, '<div>Hello world éèéêôâ</div>') !== false);

        // Special tags
        $this->assertTrue(strpos($html, 'c/000000/unsubscribe/TVNHSUQ') !== false);
        $this->assertTrue(strpos($html, 'update-profile') !== false);
        $this->assertTrue(strpos($html, 'campaigns/TVNHSUQ/web-view') !== false);
    }

    public function test_caching_just_works()
    {
        // Template
        $template = Mockery::mock(new Template(['name' => 'Template', 'content' => 'Original']));
        $template->generateUid();

        // List
        $list = Mockery::mock(new MailList());
        $list->generateUid();

        // Campaign
        $campaign = Mockery::mock(new Campaign(['name' => 'Campaign']));
        $campaign->defaultMailList = $list;
        $campaign->template = $template;

        // Flush any cache
        $campaign->clearCache();

        // Get
        $html = $campaign->getHtmlContent();
        $cachedHtml = $campaign->getHtmlContent($subscriber = null, $messageId = null, $server = null, $fromCache = true, $seconds = 1);

        $this->assertEquals($html, '<!DOCTYPE html><html><body><p>Original</p></body></html>');
        $this->assertEquals($html, $cachedHtml);

        $campaign->template->content = 'Updated';
        $html = $campaign->getHtmlContent();
        $cachedHtml = $campaign->getHtmlContent($subscriber = null, $messageId = null, $server = null, $fromCache = true, $seconds = 1);
        $this->assertEquals($html, '<!DOCTYPE html><html><body><p>Updated</p></body></html>');
        $this->assertEquals($cachedHtml, '<!DOCTYPE html><html><body><p>Original</p></body></html>');

        // wait 2 seconds for cache to expire
        sleep(2);
        $cachedHtml = $campaign->getHtmlContent($subscriber = null, $messageId = null, $server = null, $fromCache = true);
        $this->assertEquals($cachedHtml, $html);
    }
}
