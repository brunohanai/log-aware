<?php

namespace brunohanai\LogAware\Marker;

class MemcacheManager implements IMarkerManager
{
    const TTL = 6000;

    /** @var $memcache \Memcache */
    private $memcache;

    private function connect()
    {
        $this->memcache = new \Memcache();
        $this->memcache->addserver('localhost');
    }

    private function disconnect()
    {
        $this->memcache->close();
    }

    public function retrieveMark($filepath)
    {
        $this->connect();

        $mark = $this->memcache->get($filepath);

        $this->disconnect();

        return $mark;
    }

    public function saveMark($filepath, $mark)
    {
        $this->connect();

        $this->memcache->set($filepath, $mark, 0, self::TTL);

        $this->disconnect();
    }
}