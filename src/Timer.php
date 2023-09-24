<?php

declare(strict_types=1);

namespace Brunty\Cigar;

interface Timer
{
    public function start(): float;

    public function stop(): float;
}
