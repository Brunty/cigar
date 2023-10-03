<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class SystemTimer implements Timer
{
    public function now(): float
    {
        return microtime(true);
    }
}
