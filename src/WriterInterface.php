<?php

namespace Brunty\Cigar;

interface WriterInterface
{
    public function writeErrorLine(string $message);

    public function writeResults(int $numberOfPassedResults, int $numberOfResults, bool $passed, float $timeDiff, Result ...$results);
}
