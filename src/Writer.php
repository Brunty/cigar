<?php

namespace Brunty\Cigar;

interface Writer
{
    public function writeErrorLine(string $message): void;

    public function writeResults(int $numberOfPassedResults, int $numberOfResults, bool $passed, float $timeDiff, Result ...$results): void;
}
