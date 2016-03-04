<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Action\SlackAction;

class SlackActionTest extends \PHPUnit_Framework_TestCase
{
    public function testDoAction()
    {
        $content = 'phpunit...';

        $options = array(
            'webhook_url' => 'https://hooks.slack.com/services/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',
            'channel' => '#random'
        );

        $action = new SlackAction($options);
        $action->doAction($content);
    }
}