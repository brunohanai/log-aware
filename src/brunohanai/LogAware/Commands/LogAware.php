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

use Symfony\Component\EventDispatcher\EventDispatcher;
use brunohanai\LogAware\EventDispatcher\Events;
use brunohanai\LogAware\EventDispatcher\Listener\LoggerSubscriber;
use brunohanai\LogAware\EventDispatcher\Listener\StopwatchSubscriber;
use brunohanai\LogAware\EventDispatcher\Event\SystemEvent;

if (count($argv) === 1) {
    error_log('LogAware: Missing config filepath. Exiting...');
    exit();
}

$config = new Config(new ConfigDefinition(), new Processor(), new Yaml());
$config->loadConfigFile($argv[1]);

$systemConfig = $config->getSystem();

$stopwatch = new Stopwatch();
$logger = new Logger('log-aware', array(
    new StreamHandler($systemConfig[Config::SYSTEM_LOG_FILEPATH_KEY], $systemConfig[Config::SYSTEM_LOG_LEVEL_KEY])
));

/** Dispatcher */
$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new StopwatchSubscriber());
$dispatcher->addSubscriber(new LoggerSubscriber($logger));

$dispatcher->dispatch(Events::SYSTEM_START, new SystemEvent($stopwatch));

$worker = new Worker(
    $config,
    new Reader(new Marker(new MemcacheManager(new Memcache()))),
    new Parser(),
    new ActionContainer($config),
    $dispatcher,
    $stopwatch
);
$worker->execute();

$dispatcher->dispatch(Events::SYSTEM_END, new SystemEvent($stopwatch));