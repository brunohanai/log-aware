<?php
require 'vendor/autoload.php';

use brunohanai\LogAware\Config;
use brunohanai\LogAware\Worker;
use brunohanai\LogAware\Reader\Reader;
use brunohanai\LogAware\Marker\Marker;
use brunohanai\LogAware\Marker\MemcacheManager;
use brunohanai\LogAware\Parser\Parser;

$a1 = '/media/sf_projects/log-aware/log-aware.yml'; // será recebido pela linha de comando

$config = new Config($a1);

$worker = new Worker($config, new Reader(new Marker(new MemcacheManager())), new Parser());

foreach($config->getFiles() as $file) {
    $worker->execute($file['filepath'], $file['filters']);
}





