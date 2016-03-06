<?php

namespace brunohanai\LogAware\Config;

use Mockery;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadConfigFile_withValidFile_shouldWork()
    {
        $configDefinition = Mockery::mock('brunohanai\LogAware\Config\ConfigDefinition');

        $processor = Mockery::mock('Symfony\Component\Config\Definition\Processor');
        $processor->shouldReceive('processConfiguration');

        $yaml = Mockery::mock('Symfony\Component\Yaml\Yaml');
        $yaml->shouldReceive('parse')->andReturn(array());

        $config = new Config($configDefinition, $processor, $yaml);
        $config->loadConfigFile('/var/log/boot.log');
    }

    /**
     * @expectedException \Exception
     */
    public function testLoadConfigFile_withInvalidFile_shouldThrowAnException()
    {
        $configDefinition = Mockery::mock('brunohanai\LogAware\Config\ConfigDefinition');

        $processor = Mockery::mock('Symfony\Component\Config\Definition\Processor');
        $processor->shouldReceive('processConfiguration')->andThrow('\Exception');

        $yaml = Mockery::mock('Symfony\Component\Yaml\Yaml');
        $yaml->shouldReceive('parse')->andReturn(array());

        $config = new Config($configDefinition, $processor, $yaml);
        $config->loadConfigFile('/var/log/boot.log');
    }
}