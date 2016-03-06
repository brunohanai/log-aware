<?php

namespace brunohanai\LogAware\Action;

use brunohanai\LogAware\Config\Config;

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
        $actionsConfig = $this->config->getActions();

        if (!isset($actionsConfig[$name])) {
            throw new \Exception(sprintf('Error on creating Action. This Action does not exists. [action_name=%s]', $name));
        }

        $actionConfig = $actionsConfig[$name];
        $actionOptions = $actionConfig[Config::ACTIONS_OPTIONS_KEY];

        switch($actionConfig[Config::ACTIONS_TYPE_KEY]) {
            case Config::ACTION_TYPE_LOG:
                $action = new LogAction($name, $actionOptions);
                break;
            case Config::ACTION_TYPE_SLACK:
                $action = new SlackAction($name, $actionOptions);
                break;
            case Config::ACTION_TYPE_MAIL:
                $action = new MailAction($name, $actionOptions);
                break;
            default:
                return null;
        }

        $this->actions->offsetSet($name, $action);

        return $action;
    }
}