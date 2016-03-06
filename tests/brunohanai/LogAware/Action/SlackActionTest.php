<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Action\SlackAction;
use brunohanai\LogAware\Config\Config;

class SlackActionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTypeAndGetName()
    {
        $actionName = 'slack';

        $slackAction = new SlackAction($actionName, array(SlackAction::OPTION_WEBHOOK_KEY => 'webhook_url'));

        $this->assertEquals(Config::ACTION_TYPE_SLACK, $slackAction->getType());
        $this->assertEquals($actionName, $slackAction->getName());
    }
}