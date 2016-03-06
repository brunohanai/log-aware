<?php

namespace brunohanai\LogAware\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event;

class WorkerMatchedEvent extends Event
{
    private $matches;

    public function __construct($matches = array())
    {
        $this->matches = $matches;
    }

    public function getMatches()
    {
        return $this->matches;
    }
}