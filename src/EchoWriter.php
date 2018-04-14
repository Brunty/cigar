<?php

namespace Brunty\Cigar;

class EchoWriter implements WriterInterface
{
    const CONSOLE_GREEN = "\033[32m";
    const CONSOLE_RED = "\033[31m";
    const CONSOLE_RESET = "\033[0m";

    const SYMBOL_PASSED = '✓';
    const SYMBOL_FAILED = '✘';

    public function writeErrorLine(string $message)
    {
        echo self::CONSOLE_RED . $message . self::CONSOLE_RESET . PHP_EOL;
    }

    public function writeResults(int $numberOfPassedResults, int $numberOfResults, bool $passed, float $timeDiff, Result ...$results)
    {
        ob_start();

        foreach ($results as $result) {
            $this->writeLine($result);
            ob_flush();
        }

        ob_end_flush();

        $this->writeStats($numberOfPassedResults, $numberOfResults, $passed, $timeDiff);
    }

    private function writeLine(Result $result)
    {
        $contentType = '';
        list ($colour, $status) = $this->getColourAndStatus($result);
        if ($result->getUrl()->getContentType() !== null) {
            $contentType = " [{$result->getUrl()->getContentType()}:{$result->getContentType()}]";
        }

        echo "{$colour}{$status} {$result->getUrl()->getUrl()} [{$result->getUrl()->getStatus()}:{$result->getStatusCode()}]{$contentType} {$result->getUrl()->getContent()}" . self::CONSOLE_RESET . PHP_EOL;
    }

    private function writeStats(int $numberOfPassedResults, int $numberOfResults, bool $passed, float $timeDiff)
    {
        $color = self::CONSOLE_GREEN;
        $reset = self::CONSOLE_RESET;

        if ( ! $passed) {
            $color = self::CONSOLE_RED;
        }

        echo PHP_EOL . "[{$color}{$numberOfPassedResults}/{$numberOfResults}{$reset}] passed in {$timeDiff}s" . PHP_EOL . PHP_EOL;
    }

    private function getColourAndStatus(Result $result): array
    {
        $passed = $result->hasPassed();
        $colour = self::CONSOLE_GREEN;
        $status = self::SYMBOL_PASSED;

        if ( ! $passed) {
            $colour = self::CONSOLE_RED;
            $status = self::SYMBOL_FAILED;
        }

        return [$colour, $status];
    }
}
