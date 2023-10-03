<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class QuietWriter implements Writer
{
    public function writeErrorLine(string $message): void
    {
    }

    public function writeResults(Results $results, float $timeDiff): void
    {
    }
}
