<?php

namespace Gotterdemarung\Hammer;

class PhpLocAnalyzer implements AnalyzerInterface
{
    private $exec;

    /**
     * Constructor
     *
     * @param string $exec     Binary file location
     */
    public function __construct($exec)
    {
        $this->exec     = $exec;
    }

    /**
     * Returns score for provided target
     *
     * @param string $target
     * @return int
     */
    public function getScore($target)
    {
        $command = "{$this->exec} --no-ansi --no-interaction $target";
        $result  = shell_exec($command);
        $data    = null;
        $lloc    = 0;
        $cloc    = 0;

        // Logical lines of code (LLOC)
        if (preg_match('/Logical Lines of Code \(LLOC\)[^0-9*]*([0-9]+)/', $result, $data)) {
            $lloc = (int) $data[1];
        }

        // Lines of comments
        if (preg_match('/Comment Lines of Code \(CLOC\)[^0-9*]*([0-9]+)/', $result, $data)) {
            $cloc = (int) $data[1];
        }

        // Calculating score
        if ($cloc > $lloc * 2) {
            // Large amount of comments
            $score = intval($lloc * 1.5);
        } elseif ($cloc >= $lloc) {
            // Normal amount of comments
            $score = $lloc;
        } else {
            // Few comments
            $score = intval($lloc * .75);
        }

        return -$score;
    }
}