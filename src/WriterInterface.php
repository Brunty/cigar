<?php

namespace Brunty\Cigar;

interface WriterInterface
{
    public function writeErrorLine(string $message);

    public function writeLine(Result $result);

    public function writeStats(int $numberOfPassedResults, int $numberOfResults, bool $passed, float $timeDiff);
}
