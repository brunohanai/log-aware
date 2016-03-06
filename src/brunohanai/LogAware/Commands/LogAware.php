<?php

require __DIR__.'/../../../../vendor/autoload.php';

use brunohanai\LogAware\Config\Config;
use brunohanai\LogAware\Config\ConfigDefinition;
use brunohanai\LogAware\Worker;
use brunohanai\LogAware\Reader\Reader;
use brunohanai\LogAware\Marker\Marker;
use brunohanai\LogAware\Marker\MemcacheManager;
use brunohanai\LogAware\Parser\Parser;
use brunohanai\LogAware\Action\ActionContainer;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

if (count($argv) === 1) {
    error_log('LogAware: Missing config filepath. Exiting...');
    exit();
}

$stopwatch = new Stopwatch();

$config = new Config(new ConfigDefinition(), new Processor(), new Yaml());
$config->loadConfigFile($argv[1]);

$systemConfig = $config->getSystem();

$logger = new Logger('log-aware', array(
    new StreamHandler($systemConfig[Config::SYSTEM_LOG_FILEPATH_KEY], $systemConfig[Config::SYSTEM_LOG_LEVEL_KEY])
));

$worker = new Worker($config, new Reader(new Marker(new MemcacheManager(new Memcache()))), new Parser(), new ActionContainer($config), $logger);

$logger->info('Starting LogAware...');
$stopwatch->start('LogAware');

foreach($config->getFiles() as $file) {
    $stopwatchName = sprintf('LogAware/File[%s]', $file[Config::FILES_FILEPATH_KEY]);
    $stopwatch->start($stopwatchName);

    $filepath = $file[Config::FILES_FILEPATH_KEY];

    $logger->debug(sprintf('Reading %s...', $filepath));
    $worker->execute($file[Config::FILES_FILEPATH_KEY], $file[Config::FILES_FILTERS_KEY]);

    $stopwatchFileResult = $stopwatch->stop($stopwatchName);
    $logger->info(sprintf('Done. [file=%s] [duration=%sms]', $filepath, $stopwatchFileResult->getDuration()));
}

$stopwatchResult = $stopwatch->stop('LogAware');
$logger->info(sprintf('LogAware completed. [duration=%sms]', $stopwatchResult->getDuration()));