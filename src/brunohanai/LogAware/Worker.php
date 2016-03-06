<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Config\Config;
use brunohanai\LogAware\Action\ActionContainer;
use brunohanai\LogAware\Parser\Parser;
use brunohanai\LogAware\Reader\Reader;
use Psr\Log\LoggerInterface;

class Worker
{
    private $config;
    private $reader;
    private $parser;
    private $actionContainer;
    private $logger;

    public function __construct(Config $config, Reader $reader, Parser $parser, ActionContainer $action_container, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->reader = $reader;
        $this->parser = $parser;
        $this->actionContainer = $action_container;
        $this->logger = $logger;
    }

    public function execute($filepath, array $filters)
    {
        $content = $this->reader->read($filepath, 500);

        // filters
        foreach($filters as $filter) {
            $this->logger->debug(sprintf('Filtering... [regex=%s]', $filter[Config::FILES_FILTERS_REGEX_KEY]));

            $matches = $this->parser->parse($content, $filter[Config::FILES_FILTERS_REGEX_KEY]);

            $this->logger->debug(sprintf('Matched count: %s', count($matches)));

            // actions
            if (count($matches) > 0) {
                foreach($filter[Config::FILES_FILTERS_ACTIONS_KEY] as $action_name) {
                    $this->logger->debug(sprintf('Executing Action... [action_name=%s]', $action_name));

                    $msg = sprintf("[LogAware - %s]\n\n%s",
                        $filter[Config::FILES_FILTERS_DESCRIPTION_KEY],
                        implode("\n",$matches)
                    );

                    $action = $this->actionContainer->getAction($action_name);
                    $action->doAction($msg);
                }
            }
        }
    }
}
