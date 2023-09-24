<?php

declare(strict_types=1);

namespace Brunty\Cigar;

interface Writer
{
    public function writeErrorLine(string $message): void;

    public function writeResults(Results $results, float $timeDiff): void;
}
