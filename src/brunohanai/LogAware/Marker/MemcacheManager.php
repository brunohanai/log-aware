<?php

namespace brunohanai\LogAware\Marker;

class MemcacheManager implements IMarkerManager
{
    const TTL = 86400;

    /** @var $memcache \Memcache */
    private $memcache;

    public function __construct(\Memcache $memcache, $host = 'localhost', $port = 11211)
    {
        $this->memcache = $memcache;
        $this->memcache->addserver($host);

        if ($this->memcache->connect($host) === false) {
            throw new \Exception(sprintf('LogAware: Memcache, connection refused. [host=%s] [port=%s]', $host, $port));
        }
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
        return $this->memcache->set($filepath, $mark, 0, self::TTL);
    }
}