<?php

namespace brunohanai\LogAware\Action;

use brunohanai\LogAware\Config;

class ActionContainer
{
    private $config;
    private $actions;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->actions = new \ArrayIterator();
    }

    public function getAction($name)
    {
        if ($this->actions->offsetExists($name)) {
            return $this->actions->offsetGet($name);
        }

        return $this->addAction($name);
    }

    private function addAction($name)
    {
        $options = $this->config[Config::ACTIONS_KEY][$name];

        switch($name) {
            case Config::ACTIONS_LOG_KEY:
                $action = new LogAction($options);
                break;
            default:
                $action = null;
        }

        if ($action != null) {
            $this->actions->offsetSet($name, $action);
        }

        return $action;
    }
}