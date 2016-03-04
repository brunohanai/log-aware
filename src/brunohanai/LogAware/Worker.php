<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Action\ActionContainer;
use brunohanai\LogAware\Parser\Parser;
use brunohanai\LogAware\Reader\Reader;

class Worker
{
    const MSG_FORMAT = "[LogAware - %s]\n\n";

    private $config;
    private $reader;
    private $parser;
    private $container;

    public function __construct(Config $config, Reader $reader, Parser $parser, ActionContainer $container)
    {
        $this->config = $config;
        $this->reader = $reader;
        $this->parser = $parser;
        $this->container = $container;
    }

    public function execute($filepath, array $filters)
    {
        $content = $this->reader->read($filepath, 500);

        // executar os filtros (regex)
        foreach($filters as $filter) {
            $matches = $this->parser->parse($content, $filter[Config::FILES_FILTERS_REGEX_KEY]);

            // executar as ações
            if (count($matches) > 0) {
                foreach($filter[Config::FILES_FILTERS_ACTIONS_KEY] as $action_name) {
                    $description = sprintf(self::MSG_FORMAT, $filter[Config::FILES_FILTERS_DESCRIPTION_KEY]);

                    $action = $this->container->getAction($action_name);

                    $action->doAction($description.implode("\n",$matches));
                }
            }
        }
    }
}
