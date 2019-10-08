<?php

namespace Brunty\Cigar;

interface WriterInterface
{
    /**
     * @return void
     */
    public function writeErrorLine(string $message);

    /**
     * @return void
     */
    public function writeResults(int $numberOfPassedResults, int $numberOfResults, bool $passed, float $timeDiff, Result ...$results);
}
