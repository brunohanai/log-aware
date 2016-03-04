<?php

namespace brunohanai\LogAware;

use Symfony\Component\Yaml\Yaml;

class Config
{
    const KEY = 'log_aware';
    const PARAMETERS_KEY = 'parameters';
    const PARAMETERS_LINES_KEY = 'lines';

    const ACTIONS_KEY = 'actions';
    const ACTIONS_TYPE_KEY = 'type';
    const ACTIONS_OPTIONS_KEY = 'options';

    const ACTION_TYPE_LOG = 'log';
    const ACTION_TYPE_SLACK = 'slack';
    const ACTION_TYPE_MAIL= 'mail';

    const FILES_KEY = 'files';
    const FILES_FILEPATH_KEY = 'filepath';
    const FILES_FILTERS_KEY = 'filters';
    const FILES_FILTERS_DESCRIPTION_KEY = 'description';
    const FILES_FILTERS_REGEX_KEY = 'regex';
    const FILES_FILTERS_ACTIONS_KEY = 'actions';

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

    public function getActions()
    {
        return $this->config[self::ACTIONS_KEY];
    }
}