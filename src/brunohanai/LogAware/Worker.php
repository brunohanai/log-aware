<?php

namespace brunohanai\LogAware;

use brunohanai\LogAware\Parser\Parser;
use brunohanai\LogAware\Reader\Reader;

class Worker
{
    private $config;
    private $reader;
    private $parser;

    public function __construct(Config $config, Reader $reader, Parser $parser)
    {
        $this->config = $config;
        $this->reader = $reader;
        $this->parser = $parser;
    }

    public function execute($filepath, array $filters)
    {
        //$content = $this->reader->read($filepath);
        $content = <<<HEREDOC
[Tue Feb 23 22:16:14.094674 2016] [core:notice] [pid 1457] AH00094: Command line: '/usr/sbin/apache2'
[Tue Feb 23 22:22:40.497907 2016] [mpm_prefork:notice] [pid 1457] AH00169: caught SIGTERM, shutting down
[Tue Feb 23 22:22:41.586970 2016] [mpm_prefork:notice] [pid 3768] AH00163: Apache/2.4.7 (Ubuntu) PHP/5.5.9-1ubuntu4.14 configured -- resuming normal operations
[Tue Feb 23 22:22:41.587010 2016] [core:notice] [pid 3768] AH00094: Command line: '/usr/sbin/apache2'
[Tue Feb 23 22:24:46.793930 2016] [mpm_prefork:notice] [pid 3768] AH00169: caught SIGTERM, shutting down
[Tue Feb 23 22:24:47.876969 2016] [mpm_prefork:notice] [pid 4341] AH00163: Apache/2.4.7 (Ubuntu) PHP/5.5.9-1ubuntu4.14 configured -- resuming normal operations
[Tue Feb 23 22:24:47.877012 2016] [core:notice] [pid 4341] AH00094: Command line: '/usr/sbin/apache2'
HEREDOC;

        foreach($filters as $filter) {
            $matches = $this->parser->parse($content, $filter[Config::FILES_FILTERS_REGEX_KEY]);

            var_dump($matches);
        }
    }
}
