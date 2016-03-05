<?php

namespace brunohanai\LogAware\Marker;

class MemcacheManager implements IMarkerManager
{
    const TTL = 6000;

    /** @var $memcache \Memcache */
    private $memcache;

    public function __construct(\Memcache $memcache, $host = 'localhost')
    {
        $this->memcache = $memcache;
        $this->memcache->addserver($host);
    }

    public function __destruct()
    {
        $this->memcache->close();
    }

    public function retrieveMark($filepath)
    {
        return $this->memcache->get($filepath);
    }

    public function saveMark($filepath, $mark)
    {
        $this->memcache->set($filepath, $mark, 0, self::TTL);
    }
}