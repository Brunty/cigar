<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class EchoWriter implements Writer
{
    private const CONSOLE_GREEN = "\033[32m";
    private const CONSOLE_RED = "\033[31m";
    private const CONSOLE_RESET = "\033[0m";
    private const SYMBOL_PASSED = '✓';
    private const SYMBOL_FAILED = '✘';

    public function writeErrorLine(string $message): void
    {
        echo self::CONSOLE_RED . $message . self::CONSOLE_RESET . PHP_EOL;
    }

    public function writeResults(
        int $numberOfPassedResults,
        int $numberOfResults,
        bool $passed,
        float $timeDiff,
        Result ...$results
    ): void {
        ob_start();

        foreach ($results as $result) {
            $this->writeLine($result);
        }

        ob_end_flush();

        $this->writeStats($numberOfPassedResults, $numberOfResults, $passed, $timeDiff);
    }

    private function writeLine(Result $result): void
    {
        $contentType = '';
        [$colour, $status] = $this->getColourAndStatus($result);
        if ($result->url->contentType !== '') {
            $contentType = sprintf(' [%s:%s]', $result->url->contentType, $result->contentType);
        }

        echo sprintf(
            '%s%s %s [%s:%s]%s %s' . self::CONSOLE_RESET . PHP_EOL,
            $colour,
            $status,
            $result->url->url,
            $result->url->status,
            $result->statusCode,
            $contentType,
            $result->url->content
        );
    }

    private function writeStats(int $numberOfPassedResults, int $numberOfResults, bool $passed, float $timeDiff): void
    {
        $color = self::CONSOLE_GREEN;
        $reset = self::CONSOLE_RESET;

        if (!$passed) {
            $color = self::CONSOLE_RED;
        }

        echo sprintf(
            PHP_EOL . '[%s%s/%s%s] passed in %ss' . PHP_EOL . PHP_EOL,
            $color,
            $numberOfPassedResults,
            $numberOfResults,
            $reset,
            $timeDiff
        );
    }

    private function getColourAndStatus(Result $result): array
    {
        $passed = $result->hasPassed();
        $colour = self::CONSOLE_GREEN;
        $status = self::SYMBOL_PASSED;

        if (!$passed) {
            $colour = self::CONSOLE_RED;
            $status = self::SYMBOL_FAILED;
        }

        return [$colour, $status];
    }
}
