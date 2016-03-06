<?php

namespace brunohanai\LogAware\EventDispatcher\Event;

use brunohanai\LogAware\Action\IAction;
use Symfony\Component\EventDispatcher\Event;

class WorkerActionDoneEvent extends Event
{
    private $action;
    private $matches;

    public function __construct(IAction $action, $matches = array())
    {
        $this->action = $action;
        $this->matches = $matches;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getMatches()
    {
        return $this->matches;
    }
}