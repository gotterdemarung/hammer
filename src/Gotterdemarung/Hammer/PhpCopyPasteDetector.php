<?php

namespace Gotterdemarung\Hammer;

class PhpCopyPasteDetector implements AnalyzerInterface
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
        if (preg_match('/Found ([0-9]+) exact clones with ([0-9]+) duplicated lines/i', $result, $data)) {
            // Calculating score
            return $data[1] * 100 + $data[2];
        } else {
            return 0;
        }
    }
}
