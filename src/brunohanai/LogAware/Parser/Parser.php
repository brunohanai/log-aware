<?php

namespace brunohanai\LogAware\Parser;

class Parser
{
    public function parse($text, $regex)
    {
        $matches = array();

        preg_match_all($regex, $text, $matches);

        return $matches[0];
    }
}