<?php

namespace brunohanai\LogAware\Config;

use Symfony\Component\Yaml\Yaml;

class Config
{
    const ROOT_KEY = 'log_aware';

    const SYSTEM_KEY = 'system';
    const SYSTEM_LOG_FILEPATH_KEY = 'log_filepath';
    const SYSTEM_LOG_LEVEL_KEY = 'log_level';

    const SYSTEM_LOG_FILEPATH_DEFAULT = '/var/log/log-aware.log';
    const SYSTEM_LOG_LEVEL_DEFAULT = 'debug';

    const ACTIONS_KEY = 'actions';
    const ACTIONS_NAME_KEY = 'name';
    const ACTIONS_TYPE_KEY = 'type';
    const ACTIONS_OPTIONS_KEY = 'options';
    const ACTION_TYPE_LOG = 'log';
    const ACTION_TYPE_SLACK = 'slack';
    const ACTION_TYPE_MAIL = 'mail';

    const FILES_KEY = 'files';
    const FILES_FILEPATH_KEY = 'filepath';
    const FILES_FILTERS_KEY = 'filters';
    const FILES_FILTERS_DESCRIPTION_KEY = 'description';
    const FILES_FILTERS_REGEX_KEY = 'regex';
    const FILES_FILTERS_ACTIONS_KEY = 'actions';

    private $config;

    public function __construct(Yaml $yaml, $config_filepath = '/var/log/log-aware.yml')
    {
        $configFileContents = file_get_contents($config_filepath);

        if ($configFileContents === false) {
            throw new \Exception(sprintf('Config file not found. [filepath=%s]', $config_filepath));
        }

        $this->config = $yaml->parse($configFileContents);
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getSystem()
    {
        return $this->config[self::ROOT_KEY][self::SYSTEM_KEY];
    }

    public function getFiles()
    {
        return $this->config[self::ROOT_KEY][self::FILES_KEY];
    }

    public function getActions()
    {
        return $this->config[self::ROOT_KEY][self::ACTIONS_KEY];
    }
}