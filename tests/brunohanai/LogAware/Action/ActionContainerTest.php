<?php

namespace brunohanai\LogAware;

use Mockery;
use brunohanai\LogAware\Action\ActionContainer;

class ActionContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Simulando a criação de 3 ações...
     * A terceira (slack_test) já existe e não deve ser criada novamente...
     */
    public function testGetAction()
    {
        $actions = array(
            'slack_test' => array(
                'type' => 'slack',
                'options' => array(
                    'webhook_url' => 'http://',
                ),
            ),
            'logger_test' => array(
                'type' => 'log',
                'options' => array(
                    'filepath' => '/var/log/file.log',
                    'level' => 'debug',
                ),
            ),
        );

        $config = Mockery::mock('brunohanai\LogAware\Config\Config');
        $config->shouldReceive('getActions')->andReturn($actions);

        $container = new ActionContainer($config);
        $container->getAction('slack_test');
        $container->getAction('logger_test');
        $container->getAction('slack_test');

        $this->assertCount(2, $container->getActions());
    }

    /**
     * Tentar adicionar a action "logger", mas ela não está configurada.
     * Deve disparar uma exceção.
     *
     * @expectedException \Exception
     */
    public function testGetAction_withInvalidActionName_shouldThrowAnException()
    {
        $actions = array(
            'slack_test' => array(
                'type' => 'slack',
                'options' => array(),
            ),
        );

        $config = Mockery::mock('brunohanai\LogAware\Config\Config');
        $config->shouldReceive('getActions')->andReturn($actions);

        $container = new ActionContainer($config);
        $container->getAction('logger_test');
    }
}