<?php

namespace brunohanai\LogAware;

use Symfony\Component\Yaml\Yaml;

class Config
{
    const KEY = 'log_aware';
    const PARAMETERS_KEY = 'parameters';
    const PARAMETERS_LINES_KEY = 'lines';

    const ACTIONS_KEY = 'actions';
    const ACTIONS_LOG_KEY = 'log';

    const FILES_KEY = 'files';
    const FILES_FILTERS_REGEX_KEY = 'regex';

    private $lines = 10;
    private $config;

    public function __construct($config_filepath = '/var/log/log-aware.yml')
    {
        $yaml = new Yaml();

        $this->config = $yaml->parse(file_get_contents($config_filepath));
        $this->config = $this->config[self::KEY];
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getFiles()
    {
        return $this->config[self::FILES_KEY];
    }
}