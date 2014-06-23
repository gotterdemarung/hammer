<?php

namespace Gotterdemarung\Hammer;

/**
 * Interface AnalyzerInterface
 *
 * Base interface for all analyzers
 *
 * @package Gotterdemarung\Hammer
 */
interface AnalyzerInterface
{
    /**
     * Returns score for provided target
     *
     * @param string $target
     * @return int
     */
    public function getScore($target);
}
