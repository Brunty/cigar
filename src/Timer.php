<?php

declare(strict_types=1);

namespace Brunty\Cigar;

interface Timer
{
    public function now(): float;
}
