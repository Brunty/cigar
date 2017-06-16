<?php

namespace Brunty\Cigar;

class Outputter
{
    const CONSOLE_GREEN = "\033[32m";
    const CONSOLE_RED = "\033[31m";
    const CONSOLE_RESET = "\033[0m";

    const SYMBOL_PASSED = '✓';
    const SYMBOL_FAILED = '✘';

    public function output(array $results, bool $quiet = false): bool
    {
        $suitePassed = true;
        ob_start();
        foreach ($results as $result) {
            [$colour, $status, $suitePassed] = $this->getOutputAndReturn($result);

            if ( ! $quiet) {
                $this->outputLine($colour, $status, $result);
            }
            ob_flush();
        }

        ob_end_flush();

        return $suitePassed;
    }

    private function getOutputAndReturn(Result $result): array
    {
        $passed = $result->passed();
        $suitePassed = $passed;
        $colour = self::CONSOLE_GREEN;
        $status = self::SYMBOL_PASSED;

        if ( ! $passed) {
            $colour = self::CONSOLE_RED;
            $status = self::SYMBOL_FAILED;
        }

        return [$colour, $status, $suitePassed];
    }

    private function outputLine(string $colour, string $status, Result $result): void
    {
        echo "{$colour}{$status} {$result->getDomain()->getUrl()} [{$result->getDomain()->getStatus()}:{$result->getStatusCode()}] " . self::CONSOLE_RESET . PHP_EOL;
    }
}
