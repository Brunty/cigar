<?php

declare(strict_types=1);

namespace Brunty\Cigar;

use LogicException;

class SystemTimer implements Timer
{
    private ?float $startedAt = null;

    private ?float $endedAt = null;

    public function start(): float
    {
        if ($this->startedAt !== null) {
            throw new LogicException('Cannot started a timer that has already been started');
        }
        $this->startedAt = microtime(true);

        return $this->startedAt;
    }

    public function stop(): float
    {
        if ($this->startedAt === null) {
            throw new LogicException('Cannot end a timer that has not been started');
        }

        if ($this->endedAt !== null) {
            throw new LogicException('Cannot end a timer that has already been ended');
        }

        $this->endedAt = microtime(true);

        return $this->endedAt;
    }
}
