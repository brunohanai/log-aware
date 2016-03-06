<?php

require __DIR__.'/vendor/autoload.php';

use brunohanai\LogAware\Config\Config;
use brunohanai\LogAware\Config\ConfigDefinition;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;

if (count($argv) === 1) {
    echo "LogAware: Missing config filepath.\n";
    exit();
}

$config = new Config(new ConfigDefinition(), new Processor(), new Yaml());

try {
    $config->loadConfigFile($argv[1]);
    echo "LogAware: Config file seems right.\n";
} catch (\Exception $e) {
    echo $e->getMessage()."\n";
}


