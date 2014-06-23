<?php

namespace Gotterdemarung\Hammer;

/**
 * Class PhpCodeSniffer
 *
 * Performs analyze using php code sniffer
 *
 * @package Gotterdemarung\Hammer
 */
class PhpCodeSniffer implements AnalyzerInterface
{
    private $exec;
    private $standard;

    /**
     * Constructor
     *
     * @param string $exec     Binary file location
     * @param string $standard Standard used
     */
    public function __construct($exec, $standard = 'PSR2')
    {
        $this->exec     = $exec;
        $this->standard = $standard;
    }

    /**
     * Returns score for provided target
     *
     * @param string $target
     * @throws \Exception
     * @return int
     */
    public function getScore($target)
    {
        $command = "{$this->exec} --report-json --standard={$this->standard} $target";
        $result  = shell_exec($command);
        $data    = json_decode($result, true);
        $score   = 0;
        if (!is_array($data)) {
            throw new \Exception('Wrong answer format');
        }

        foreach ($data['files'] as $filename => $fileData) {
            $score += $fileData['errors'] * 5 + $fileData['warnings'];
        }

        return $score;
    }
}
