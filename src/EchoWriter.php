<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class EchoWriter implements Writer
{
    private const SYMBOL_PASSED = '✓';
    private const SYMBOL_FAILED = '✘';

    public function writeErrorLine(string $message): void
    {
        echo ConsoleColours::red() . $message . ConsoleColours::reset() . PHP_EOL;
    }

    public function writeResults(Results $results, float $timeDiff): void
    {
        ob_start();

        foreach ($results->results as $result) {
            $this->writeLine($result);
        }

        ob_end_flush();

        $this->writeStats($results, $timeDiff);
    }

    private function writeLine(Result $result): void
    {
        $contentType = '';
        [$colour, $status] = $this->getColourAndStatus($result);
        if ($result->url->contentType !== '') {
            $contentType = sprintf(' [%s:%s]', $result->url->contentType, $result->contentType);
        }

        echo sprintf(
            '%s%s %s [%s:%s]%s %s' . ConsoleColours::reset() . PHP_EOL,
            $colour,
            $status,
            $result->url->url,
            $result->url->status,
            $result->statusCode,
            $contentType,
            $result->url->content
        );
    }

    private function writeStats(Results $results, float $timeDiff): void
    {
        $color = ConsoleColours::green();
        $reset = ConsoleColours::reset();

        if ($results->hasPassed() === false) {
            $color = ConsoleColours::red();
        }

        echo sprintf(
            PHP_EOL . '[%s%s/%s%s] passed in %ss' . PHP_EOL . PHP_EOL,
            $color,
            $results->numberOfPassedResults(),
            $results->totalNumberOfResults(),
            $reset,
            $timeDiff
        );
    }

    /**
     * @return string[]
     */
    private function getColourAndStatus(Result $result): array
    {
        $passed = $result->hasPassed();
        $colour = ConsoleColours::green();
        $status = self::SYMBOL_PASSED;

        if ($passed === false) {
            $colour = ConsoleColours::red();
            $status = self::SYMBOL_FAILED;
        }

        return [$colour, $status];
    }
}
