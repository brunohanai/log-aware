<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Action\ActionContainer;

class ActionContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Simulando a criação de 3 ações...
     * A terceira (slack_test) já existe e não deve ser criada novamente...
     */
    public function testAddAction()
    {
        $config = new Config(__DIR__.'/../../../../log-aware.yml');

        $container = new ActionContainer($config);
        $container->getAction('slack_test');
        $container->getAction('logger');
        $container->getAction('slack_test');

        $this->assertCount(2, $container->getActions());
    }
}