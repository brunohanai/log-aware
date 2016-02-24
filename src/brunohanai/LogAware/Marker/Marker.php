<?php

namespace brunohanai\LogAware\Marker;

class Marker
{
    private $manager;

    public function __construct(IMarkerManager $manager)
    {
        $this->manager = $manager;
    }

    public function mark($filepath)
    {
        // generate mark
        $mark = sprintf('[log-aware#%s]', rand(0, 999));

        // put mark in file
        file_put_contents($filepath, sprintf("\n%s\n", $mark), FILE_APPEND);

        // save mark in MarkerManager
        $this->manager->saveMark($filepath, $mark);
    }

    public function retrieveMark($filepath)
    {
        return $this->manager->retrieveMark($filepath);
    }
}