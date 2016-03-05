<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Action\ActionContainer;
use brunohanai\LogAware\Parser\Parser;
use brunohanai\LogAware\Reader\Reader;

class Worker
{
    private $config;
    private $reader;
    private $parser;
    private $actionContainer;

    public function __construct(Config $config, Reader $reader, Parser $parser, ActionContainer $action_container)
    {
        $this->config = $config;
        $this->reader = $reader;
        $this->parser = $parser;
        $this->actionContainer = $action_container;
    }

    public function execute($filepath, array $filters)
    {
        $content = $this->reader->read($filepath, 500);

        // filters
        foreach($filters as $filter) {
            $matches = $this->parser->parse($content, $filter[Config::FILES_FILTERS_REGEX_KEY]);

            // actions
            if (count($matches) > 0) {
                foreach($filter[Config::FILES_FILTERS_ACTIONS_KEY] as $action_name) {
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
