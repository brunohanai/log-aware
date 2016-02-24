<?php

namespace brunohanai\LogAware\Marker;

interface IMarkerManager
{
    public function retrieveMark($filepath);

    public function saveMark($filepath, $mark);
}