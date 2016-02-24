<?php

namespace brunohanai\LogAware;

use Symfony\Component\Yaml\Yaml;

class Config
{
    const KEY = 'log_aware';
    const PARAMETERS_KEY = 'parameters';
    const PARAMETERS_LINES_KEY = 'lines';

    const FILES_KEY = 'files';

    private $lines = 10;
    private $config;

    public function __construct($config_filepath = '/var/log/log-aware.yml')
    {
        $yaml = new Yaml();

        $this->config = $yaml->parse(file_get_contents($config_filepath));
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getFiles()
    {
        return $this->config[self::KEY][self::FILES_KEY];
    }
}