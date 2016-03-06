<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Action\LogAction;
use brunohanai\LogAware\Config\Config;

class LogActionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTypeAndGetName()
    {
        $actionName = 'log';

        $logAction = new LogAction($actionName, array(LogAction::OPTION_FILEPATH_KEY => 'file', LogAction::OPTION_LEVEL_KEY => 'level'));

        $this->assertEquals(Config::ACTION_TYPE_LOG, $logAction->getType());
        $this->assertEquals($actionName, $logAction->getName());
    }
}