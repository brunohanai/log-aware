<?php

namespace brunohanai\LogAware\EventDispatcher\Listener;

use brunohanai\LogAware\EventDispatcher\Event\WorkerActionDoneEvent;
use brunohanai\LogAware\EventDispatcher\Event\WorkerExecuteFileEvent;
use brunohanai\LogAware\EventDispatcher\Event\WorkerMatchedEvent;
use Psr\Log\LoggerInterface;
use brunohanai\LogAware\Enums\StopwatchEnum;
use brunohanai\LogAware\EventDispatcher\Events;
use brunohanai\LogAware\EventDispatcher\Event\SystemEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoggerSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::SYSTEM_START => array('onSystemStart', 50),
            Events::SYSTEM_END => array('onSystemStop', 50),
            Events::WORKER_EXECUTE_FILE_START => array('onWorkerExecuteFileStart', 50),
            Events::WORKER_EXECUTE_FILE_END => array('onWorkerExecuteFileEnd', 50),
            Events::WORKER_FILE_MATCHED => array('onWorkerMatched', 50),
            Events::WORKER_ACTION_DONE => array('onWorkerActionDone', 50)
        );
    }

    public function onSystemStart()
    {
        $this->logger->info('Log-Aware has started...');
    }

    public function onSystemStop(SystemEvent $event)
    {
        $stopwatchEvent = $event->getStopwatch()->getEvent(StopwatchEnum::NAME_SYSTEM);

        $this->logger->info(sprintf('Log-Aware completed. [duration=%sms]', $stopwatchEvent->getDuration()));
    }

    public function onWorkerExecuteFileStart(WorkerExecuteFileEvent $event)
    {
        $this->logger->debug('File: '.$event->getFilepath());
    }

    public function onWorkerExecuteFileEnd(WorkerExecuteFileEvent $event)
    {
        $stopwatchEvent = $event->getStopwatch()->getEvent($event->getStopwatchEventName());

        $this->logger->debug(sprintf('File end. [duration=%sms]', $stopwatchEvent->getDuration()));
    }

    public function onWorkerMatched(WorkerMatchedEvent $event)
    {
        $this->logger->debug('Matched count: '.count($event->getMatches()));
    }

    public function onWorkerActionDone(WorkerActionDoneEvent $event)
    {
        $msg = implode(' ', $event->getMatches());

        $this->logger->debug(sprintf('Action [type=%s] [name=%s] executed with [message=%s%s].',
            $event->getAction()->getType(),
            $event->getAction()->getName(),
            substr($msg, 0, 50),
            strlen($msg) > 50 ? '...' : ''
        ));
    }
}