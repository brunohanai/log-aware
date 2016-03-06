<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Config\Config;
use brunohanai\LogAware\Action\ActionContainer;
use brunohanai\LogAware\EventDispatcher\Event\WorkerActionDoneEvent;
use brunohanai\LogAware\EventDispatcher\Event\WorkerExecuteFileEvent;
use brunohanai\LogAware\EventDispatcher\Event\WorkerMatchedEvent;
use brunohanai\LogAware\EventDispatcher\Events;
use brunohanai\LogAware\Parser\Parser;
use brunohanai\LogAware\Reader\Reader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;

class Worker
{
    private $config;
    private $reader;
    private $parser;
    private $actionContainer;
    private $dispatcher;
    private $stopwatch;

    public function __construct(Config $config, Reader $reader, Parser $parser, ActionContainer $action_container, EventDispatcher $dispatcher, Stopwatch $stopwatch)
    {
        $this->config = $config;
        $this->reader = $reader;
        $this->parser = $parser;
        $this->actionContainer = $action_container;
        $this->dispatcher = $dispatcher;
        $this->stopwatch = $stopwatch;
    }

    public function execute($files)
    {
        if (!is_array($files)) {
            return;
        }

        foreach($files as $file) {
            $this->executeFile($file[Config::FILES_FILEPATH_KEY], $file[Config::FILES_FILTERS_KEY]);
        }
    }

    public function executeFile($filepath, array $filters)
    {
        $this->dispatcher->dispatch(Events::WORKER_EXECUTE_FILE_START, new WorkerExecuteFileEvent($filepath, $this->stopwatch));

        $content = $this->reader->read($filepath, 500);

        // filters
        foreach($filters as $filter) {
            $matches = $this->parser->parse($content, $filter[Config::FILES_FILTERS_REGEX_KEY]);

            $this->dispatcher->dispatch(Events::WORKER_FILE_MATCHED, new WorkerMatchedEvent($matches));

            // actions
            if (count($matches) > 0) {
                foreach($filter[Config::FILES_FILTERS_ACTIONS_KEY] as $action_name) {
                    $msg = sprintf("[LogAware - %s]\n\n%s",
                        $filter[Config::FILES_FILTERS_DESCRIPTION_KEY],
                        implode("\n",$matches)
                    );

                    $action = $this->actionContainer->getAction($action_name);
                    $action->doAction($msg);

                    $this->dispatcher->dispatch(Events::WORKER_ACTION_DONE, new WorkerActionDoneEvent($action, $matches));
                }
            }
        }

        $this->dispatcher->dispatch(Events::WORKER_EXECUTE_FILE_END, new WorkerExecuteFileEvent($filepath, $this->stopwatch));
    }
}
