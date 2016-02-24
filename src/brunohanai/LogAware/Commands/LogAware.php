<?php
require 'vendor/autoload.php';

use brunohanai\LogAware\Config;

$actions = array();
$config = new Config('/media/sf_projects/log-aware/log-aware.yml');

$files = $config->getFiles();


foreach($files as $file) {
    var_dump($file);
}





