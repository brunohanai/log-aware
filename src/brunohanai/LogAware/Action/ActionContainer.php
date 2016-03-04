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

    public function getActions()
    {
        return $this->actions;
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
        $actions = $this->config->getActions();

        if (!isset($actions[$name])) {
            throw new \Exception(sprintf('This action does not exists. [action_name=%s]', $name));
        }

        $options = $actions[$name][Config::ACTIONS_OPTIONS_KEY];

        switch($actions[$name][Config::ACTIONS_TYPE_KEY]) {
            case Config::ACTION_TYPE_LOG:
                $action = new LogAction($options);
                break;
            case Config::ACTION_TYPE_SLACK:
                $action = new SlackAction($options);
                break;
            case Config::ACTION_TYPE_MAIL:
                $action = new MailAction($options);
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