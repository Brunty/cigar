<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\SystemTimer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brunty\Cigar\SystemTimer
 */
class SystemTimerTest extends TestCase
{
    #[Test]
    public function it_returns_now(): void
    {
        $timer = new SystemTimer();

        $now = $timer->now();

        $this->assertNotSame($now, $timer->now());
    }
}
