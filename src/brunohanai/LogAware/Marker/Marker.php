<?php

namespace brunohanai\LogAware\Marker;

class Marker
{
    private $manager;

    public function __construct(IMarkerManager $manager)
    {
        $this->manager = $manager;
    }

    public function saveMark($filepath, $content)
    {
        $mark = $content;

        $this->manager->saveMark($filepath, $mark);
    }

    public function retrieveMark($filepath)
    {
        return $this->manager->retrieveMark($filepath);
    }
}