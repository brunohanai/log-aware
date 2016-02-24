<?php

namespace brunohanai\LogAware\Action;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LogAction implements IAction
{
    const NAME = 'log-aware';
    const FILEPATH_KEY = 'filepath';

    const LEVEL_KEY = 'level';

    private $logger;
    private $level;

    public function __construct(array $options = array())
    {
        $handler = new StreamHandler($options[self::FILEPATH_KEY]);
        $this->logger = new Logger(self::NAME, $handler);

        $this->level = $options[self::LEVEL_KEY];
    }

    public function doAction($content)
    {
        $this->logger->log($this->level, $content);
    }
}