<?php

namespace brunohanai\LogAware\Marker;

use Mockery;

class MemcacheManagerTest extends \PHPUnit_Framework_TestCase
{
    private $memcache;

    public function setUp()
    {
        $this->memcache = Mockery::mock('\Memcache');
        $this->memcache->shouldReceive('addserver');
        $this->memcache->shouldReceive('get');
        $this->memcache->shouldReceive('set');
        $this->memcache->shouldReceive('connect');
        $this->memcache->shouldReceive('close');
    }

    public function testSaveMark()
    {
        $host = 'localhost';
        $port = 112;

        $memcacheManager = new MemcacheManager($this->memcache, $host, $port);
        $memcacheManager->saveMark('filepath', 'mark');

        $this->memcache->shouldHaveReceived('set')->with('filepath', 'mark', 0, MemcacheManager::TTL);
    }

    public function testRetrieveMark()
    {
        $host = 'localhost';
        $port = 112;

        $memcacheManager = new MemcacheManager($this->memcache, $host, $port);
        $memcacheManager->retrieveMark('filepath');

        $this->memcache->shouldHaveReceived('get')->with('filepath');
    }
}