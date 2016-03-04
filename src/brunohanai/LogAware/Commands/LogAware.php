<?php

require __DIR__.'/../../../../vendor/autoload.php';

use brunohanai\LogAware\Config;
use brunohanai\LogAware\Worker;
use brunohanai\LogAware\Reader\Reader;
use brunohanai\LogAware\Marker\Marker;
use brunohanai\LogAware\Marker\MemcacheManager;
use brunohanai\LogAware\Parser\Parser;
use brunohanai\LogAware\Action\ActionContainer;
use Symfony\Component\Stopwatch\Stopwatch;

$a1 = __DIR__.'/../../../../log-aware.yml'; // será recebido pela linha de comando

$config = new Config($a1);
$worker = new Worker($config, new Reader(new Marker(new MemcacheManager())), new Parser(), new ActionContainer($config));

$stopwatch = new Stopwatch();

foreach($config->getFiles() as $file) {
    $stopwatchName = sprintf('LogAware/File[%s]', $file[Config::FILES_FILEPATH_KEY]);
    $stopwatch->start($stopwatchName);

    $worker->execute($file[Config::FILES_FILEPATH_KEY], $file[Config::FILES_FILTERS_KEY]);

    $stopwatchFileResult = $stopwatch->stop($stopwatchName);
    echo sprintf("StopWatch: [%s] [duration=%s] \n", $stopwatchName, $stopwatchFileResult->getDuration());
}