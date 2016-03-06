<?php

namespace brunohanai\LogAware\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Stopwatch\Stopwatch;

class SystemEvent extends Event
{
    private $stopwatch;

    public function __construct(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    public function getStopwatch()
    {
        return $this->stopwatch;
    }
}