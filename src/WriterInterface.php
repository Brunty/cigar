<?php

namespace Brunty\Cigar;

interface WriterInterface
{
    public function writeErrorLine(string $message);

    public function writeLine(Result $result);

    public function writeStats(int $numberOfPassedResults, int $numberOfResults, float $passed, float $timeDiff);
}
