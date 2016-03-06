<?php

namespace brunohanai\LogAware\EventDispatcher\Listener;

use brunohanai\LogAware\Enums\StopwatchEnum;
use brunohanai\LogAware\EventDispatcher\Event\SystemEvent;
use brunohanai\LogAware\EventDispatcher\Event\WorkerExecuteFileEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use brunohanai\LogAware\EventDispatcher\Events;

class StopwatchSubscriber implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::SYSTEM_START => array('onSystemStart', 100),
            Events::SYSTEM_END => array('onSystemStop', 100),
            Events::WORKER_EXECUTE_FILE_START => array('onWorkerExecuteFileStart', 100),
            Events::WORKER_EXECUTE_FILE_END => array('onWorkerExecuteFileEnd', 100),
        );
    }

    public function onSystemStart(SystemEvent $event)
    {
        $event->getStopwatch()->start(StopwatchEnum::NAME_SYSTEM);
    }

    public function onSystemStop(SystemEvent $event)
    {
        $event->getStopwatch()->stop(StopwatchEnum::NAME_SYSTEM);
    }

    public function onWorkerExecuteFileStart(WorkerExecuteFileEvent $event)
    {
        $event->getStopwatch()->start($event->getStopwatchEventName());
    }

    public function onWorkerExecuteFileEnd(WorkerExecuteFileEvent $event)
    {
        $event->getStopwatch()->stop($event->getStopwatchEventName());
    }
}