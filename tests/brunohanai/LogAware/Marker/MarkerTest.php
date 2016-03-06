<?php

namespace brunohanai\LogAware\Marker;

use Mockery;

class MarkerTest extends \PHPUnit_Framework_TestCase
{
    private $manager;

    public function setUp()
    {
        $this->manager = Mockery::mock('brunohanai\LogAware\Marker\IMarkerManager');
        $this->manager->shouldReceive('saveMark');
        $this->manager->shouldReceive('retrieveMark');
    }

    public function testSaveMark()
    {
        $marker = new Marker($this->manager);
        $marker->saveMark('filepath', 'content');

        $this->manager->shouldHaveReceived('saveMark')->with('filepath', 'content');
    }

    public function testRetrieveMark()
    {
        $marker = new Marker($this->manager);
        $marker->retrieveMark('filepath');

        $this->manager->shouldHaveReceived('retrieveMark')->with('filepath');
    }
}