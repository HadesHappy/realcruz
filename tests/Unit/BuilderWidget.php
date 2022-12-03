<?php

namespace Tests\Unit;

use Tests\TestCase;
use League\Pipeline\PipelineBuilder;
use Acelle\Library\HtmlHandler\TransformWidgets;

class BuilderWidget extends TestCase
{
    public function test_rss_widget()
    {
        $config = \Acelle\Model\Template::defaultRssConfig();
        $config['url'] = 'https://www.techradar.com/rss/news/software';
        $config['size'] = '5';

        $html = '
            <h1>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facere, quia.</h1>
            <div id="123"
                class="rss-widget"
                builder-element="RssElement"
                builder-draggable
                data-preview="no"
                data-config="'.base64_encode(json_encode($config)).'"
            ></div>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facere, quia.</p>
        ';

        $pipeline = new PipelineBuilder();
        $pipeline->add(new TransformWidgets());

        $html = $pipeline->build()->process($html);

        $this->assertTrue(strpos($html, 'rss-item="ItemTitle"') !== false);
    }
}
