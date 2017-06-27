<?php

namespace Brunty\Cigar;

class Outputter
{
    const CONSOLE_GREEN = "\033[32m";
    const CONSOLE_RED = "\033[31m";
    const CONSOLE_RESET = "\033[0m";

    const SYMBOL_PASSED = '✓';
    const SYMBOL_FAILED = '✘';

    /**
     * @var bool
     */
    private $isQuiet;

    public function __construct(bool $isQuiet = false)
    {
        $this->isQuiet = $isQuiet;
    }

    public static function writeErrorLine(string $message): void
    {
        echo self::CONSOLE_RED . $message . self::CONSOLE_RESET . PHP_EOL;
    }

    public function outputResults(array $results): array
    {
        $passedResults = [];

        ob_start();

        foreach ($results as $result) {
            [$colour, $status, $passed] = $this->getOutputAndReturn($result);

            if ($passed) {
                $passedResults[] = $result;
            }

            if ( ! $this->isQuiet) {
                $this->outputLine($colour, $status, $result);
                ob_flush();
            }
        }

        ob_end_flush();

        return $passedResults;
    }

    private function getOutputAndReturn(Result $result): array
    {
        $passed = $result->passed();
        $colour = self::CONSOLE_GREEN;
        $status = self::SYMBOL_PASSED;

        if ( ! $passed) {
            $colour = self::CONSOLE_RED;
            $status = self::SYMBOL_FAILED;
        }

        return [$colour, $status, $passed];
    }

    private function outputLine(string $colour, string $status, Result $result): void
    {
        echo "{$colour}{$status} {$result->getDomain()->getUrl()} [{$result->getDomain()->getStatus()}:{$result->getStatusCode()}] {$result->getDomain()->getContent()}" . self::CONSOLE_RESET . PHP_EOL;
    }

    public function outputStats(array $passedResults, array $results, float $startTime): void
    {
        $numberOfResults = count($results);
        $numberOfPassedResults = count($passedResults);
        $end = microtime(true);
        $timeDiff = round($end - $startTime, 3);
        $passed = $numberOfPassedResults === $numberOfResults;
        $color = self::CONSOLE_GREEN;
        $reset = self::CONSOLE_RESET;

        if ( ! $passed) {
            $color = self::CONSOLE_RED;
        }

        if ( ! $this->isQuiet) {
            echo PHP_EOL . "[{$color}{$numberOfPassedResults}/{$numberOfResults}{$reset}] passed in {$timeDiff}s" . PHP_EOL . PHP_EOL;
        }
    }
}
