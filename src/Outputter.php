<?php

namespace Brunty\Cigar;

use function Sodium\crypto_box_publickey_from_secretkey;

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

    public function writeErrorLine(string $message): void
    {
        if ($this->isQuiet) {
            return;
        }

        echo self::CONSOLE_RED . $message . self::CONSOLE_RESET . PHP_EOL;
    }

    public function outputResults(array $results): void
    {
        if ($this->isQuiet) {
            return;
        }

        ob_start();

        foreach ($results as $result) {
            [$colour, $status] = $this->getColourAndStatus($result);
            $this->outputLine($colour, $status, $result);
            ob_flush();
        }

        ob_end_flush();
    }

    private function getColourAndStatus(Result $result): array
    {
        $passed = $result->passed();
        $colour = self::CONSOLE_GREEN;
        $status = self::SYMBOL_PASSED;

        if ( ! $passed) {
            $colour = self::CONSOLE_RED;
            $status = self::SYMBOL_FAILED;
        }

        return [$colour, $status];
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
