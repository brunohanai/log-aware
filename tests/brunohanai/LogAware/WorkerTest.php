<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Config\Config;
use Mockery;
use brunohanai\LogAware\EventDispatcher\Event\WorkerActionDoneEvent;
use brunohanai\LogAware\EventDispatcher\Event\WorkerExecuteFileEvent;
use brunohanai\LogAware\EventDispatcher\Event\WorkerMatchedEvent;
use brunohanai\LogAware\EventDispatcher\Events;;

class WorkerTest extends \PHPUnit_Framework_TestCase
{
    private $config;
    private $reader;
    private $parser;
    private $action;
    private $actionContainer;
    private $dispatcher;
    private $stopwatch;

    public function setUp()
    {
        $this->config = Mockery::mock('brunohanai\LogAware\Config\Config');

        $this->reader = Mockery::mock('brunohanai\LogAware\Reader\Reader');
        $this->reader->shouldReceive('read');

        $this->parser = Mockery::mock('brunohanai\LogAware\Parser\Parser');
        $this->parser->shouldReceive('parse')->andReturn(array('opa', 'epa'));

        $this->action = Mockery::mock('brunohanai\LogAware\Action\IAction');
        $this->action->shouldReceive('doAction');

        $this->actionContainer = Mockery::mock('brunohanai\LogAware\Action\ActionContainer');
        $this->actionContainer->shouldReceive('getAction')->andReturn($this->action);

        $this->dispatcher = Mockery::mock('Symfony\Component\EventDispatcher\EventDispatcher');
        $this->dispatcher->shouldReceive('dispatch');

        $this->stopwatch = Mockery::mock('Symfony\Component\Stopwatch\Stopwatch');
    }

    public function testExecute_withoutFiles_shouldReturnFalse()
    {
        $this->config->shouldReceive('getFiles')->andReturn(null);

        $worker = new Worker($this->config, $this->reader, $this->parser, $this->actionContainer, $this->dispatcher, $this->stopwatch);

        $this->assertFalse($worker->execute());
    }

    public function testExecuteFile()
    {
        $worker = new Worker($this->config, $this->reader, $this->parser, $this->actionContainer, $this->dispatcher, $this->stopwatch);

        $filters = array(
            array(
                Config::FILES_FILTERS_DESCRIPTION_KEY => 'description',
                Config::FILES_FILTERS_REGEX_KEY => 'regex',
                Config::FILES_FILTERS_ACTIONS_KEY => array('slack', 'mail', 'log'),
            ),
        );

        $worker->executeFile('filepath', $filters);

        $this->dispatcher->shouldHaveReceived('dispatch');
        $this->reader->shouldHaveReceived('read');
        $this->parser->shouldHaveReceived('parse');
        $this->action->shouldHaveReceived('doAction');
    }
}