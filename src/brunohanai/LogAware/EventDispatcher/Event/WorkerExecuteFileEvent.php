<?php

namespace brunohanai\LogAware\EventDispatcher\Event;

use brunohanai\LogAware\Enums\StopwatchEnum;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Stopwatch\Stopwatch;

class WorkerExecuteFileEvent extends Event
{
    private $filepath;
    private $stopwatch;

    public function __construct($filepath, Stopwatch $stopwatch)
    {
        $this->filepath = $filepath;
        $this->stopwatch = $stopwatch;
    }

    public function getFilepath()
    {
        return $this->filepath;
    }

    public function getStopwatch()
    {
        return $this->stopwatch;
    }

    public function getStopwatchEventName()
    {
        return sprintf('%s/%s', StopwatchEnum::NAME_WORKER, $this->filepath);
    }
}