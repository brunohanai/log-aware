<?php

namespace brunohanai\LogAware\Action;

use brunohanai\LogAware\Config\Config;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogAction implements IAction
{
    const DEFAULT_NAME = 'log-aware';
    const OPTION_FILEPATH_KEY = 'filepath';
    const OPTION_LEVEL_KEY = 'level';

    private $name;
    private $logger;
    private $level;

    public function __construct($name, array $options = array())
    {
        $this->name = $name;
        $this->logger = new Logger(self::DEFAULT_NAME, array(new StreamHandler($options[self::OPTION_FILEPATH_KEY])));
        $this->level = $options[self::OPTION_LEVEL_KEY];
    }

    public function doAction($content)
    {
        return $this->logger->log($this->level, $content);
    }

    public function getType()
    {
        return Config::ACTION_TYPE_LOG;
    }

    public function getName()
    {
        return $this->name;
    }
}