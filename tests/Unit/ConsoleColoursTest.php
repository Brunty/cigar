<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\ConsoleColours;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brunty\Cigar\ConsoleColours
 */
class ConsoleColoursTest extends TestCase
{
    public static function console_codes(): array
    {
        return [
            ['reset', 0],
            ['red', 31],
            ['green', 32],
            ['yellow', 33],
            ['cyan', 36],
            ['grey', 90],
        ];
    }

    #[Test]
    #[DataProvider('console_codes')]
    public function it_returns_the_console_codes(string $method, int $code): void
    {
        $this->assertSame("\e[{$code}m", ConsoleColours::$method());
    }
}
