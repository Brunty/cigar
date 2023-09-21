<?php

namespace Brunty\Cigar;

class QuietWriter implements Writer
{
    public function writeErrorLine(string $message): void
    {
    }

    public function writeResults(int $numberOfPassedResults, int $numberOfResults, bool $passed, float $timeDiff, Result ...$results): void
    {
    }
}
