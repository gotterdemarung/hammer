<?php

namespace Gotterdemarung\Hammer;

class PhpMessDetector implements AnalyzerInterface
{
    private $exec;
    private $standard;

    /**
     * Constructor
     *
     * @param string $exec     Binary file location
     * @param string $standard Standard used
     */
    public function __construct($exec, $standard = 'cleancode, codesize, controversial, design, naming, unusedcode')
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
        $command = "{$this->exec} $target xml {$this->standard}";
        $result  = shell_exec($command);
        $xml     = new \SimpleXMLElement($result);
        $score   = 0;

        foreach ($xml->xpath('/pmd/file') as $file) {
            foreach ($file->xpath('violation') as $violation) {
                $priority = intval($violation['priority'] . '');
                $rule     = $violation['rule'] . '';
                $ruleset  = $violation['ruleset'] . '';
                $score += $this->calculateScoreFor($priority, $rule, $ruleset);
            }
        }

        return $score;
    }

    /**
     * Rises score for certain types
     *
     * @param int    $priority
     * @param string $rule
     * @param string $ruleset
     * @return int
     */
    public function calculateScoreFor($priority, $rule, $ruleset)
    {
        switch ($rule) {
            case 'StaticAccess':
                $priority *= 3;
        }
        return $priority;
    }
}
