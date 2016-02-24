<?php

namespace brunohanai\LogAware\Reader;

use brunohanai\LogAware\Marker\Marker;

class Reader
{
    /** @var $marker Marker */
    private $marker;

    public function __construct(Marker $marker)
    {
        $this->marker = $marker;
    }

    public function read($filepath, $lines = 10)
    {
        // tail the file
        $output = $this->tail($filepath, $lines);

        // get current Mark
        $mark = $this->marker->retrieveMark($filepath);

        // if Mark exists, get text after it
        if ($mark) {
            $output = substr($output, strpos($output, $mark) + strlen($mark));
        }

        // create a new Mark and put in the file
        $this->marker->mark($filepath);

        return $output;
    }

    private function tail($filepath, $lines)
    {
        $adaptive = true;

        // Open file
        $f = @fopen($filepath, "rb");
        if ($f === false) return false;
        // Sets buffer size
        if (!$adaptive) $buffer = 4096;
        else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
        // Jump to last character
        fseek($f, -1, SEEK_END);
        // Read it and adjust line number if necessary
        // (Otherwise the result would be wrong if file doesn't end with a blank line)
        if (fread($f, 1) != "\n") $lines -= 1;

        // Start reading
        $output = '';
        $chunk = '';
        // While we would like more
        while (ftell($f) > 0 && $lines >= 0) {
            // Figure out how far back we should jump
            $seek = min(ftell($f), $buffer);
            // Do the jump (backwards, relative to where we are)
            fseek($f, -$seek, SEEK_CUR);
            // Read a chunk and prepend it to our output
            $output = ($chunk = fread($f, $seek)) . $output;
            // Jump back to where we started reading
            fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
            // Decrease our line counter
            $lines -= substr_count($chunk, "\n");
        }
        // While we have too many lines
        // (Because of buffer size we might have read too many)
        while ($lines++ < 0) {
            // Find first newline and remove all text before that
            $output = substr($output, strpos($output, "\n") + 1);
        }
        // Close file and return
        fclose($f);

        return trim($output);
    }
}