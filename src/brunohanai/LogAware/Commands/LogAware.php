<?php

require __DIR__.'/../../../../vendor/autoload.php';

use brunohanai\LogAware\Config\Config;
use brunohanai\LogAware\Worker;
use brunohanai\LogAware\Reader\Reader;
use brunohanai\LogAware\Marker\Marker;
use brunohanai\LogAware\Marker\MemcacheManager;
use brunohanai\LogAware\Parser\Parser;
use brunohanai\LogAware\Action\ActionContainer;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Yaml\Yaml;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Config\Definition\Processor;
use brunohanai\LogAware\Config\Configuration;

if (count($argv) === 1) {
    error_log('LogAware: Missing config filepath. Exiting...');
    exit();
}

$configFilepath = $argv[1];

$config = new Config(new Yaml(), $configFilepath);

$processor = new Processor();
$configuration = new Configuration();

try {
    $processed = $processor->processConfiguration($configuration, $config->getConfig());
} catch (\Exception $e) {
    error_log(sprintf('LogAware: Wrong config. Exiting... [error_msg=%s]', $e->getMessage()));
    exit();
}

$stopwatch = new Stopwatch();

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