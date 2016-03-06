<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Action\MailAction;
use brunohanai\LogAware\Config\Config;

class MailActionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTypeAndGetName()
    {
        $actionName = 'mail';

        $mailAction = new MailAction($actionName, array(
            MailAction::OPTION_HOST_KEY => 'host',
            MailAction::OPTION_PORT_KEY => 'port',
            MailAction::OPTION_USERNAME_KEY => 'user',
            MailAction::OPTION_PASSWORD_KEY => 'pass',
        ));

        $this->assertEquals(Config::ACTION_TYPE_MAIL, $mailAction->getType());
        $this->assertEquals($actionName, $mailAction->getName());
    }
}