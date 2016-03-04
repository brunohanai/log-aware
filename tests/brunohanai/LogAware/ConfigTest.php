<?php

namespace brunohanai\LogAware;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $config = new Config(__DIR__.'/../../../log-aware.yml');

        var_dump($config->getFiles());
    }
}