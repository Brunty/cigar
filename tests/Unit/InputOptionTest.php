<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\InputOption;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
* @covers \Brunty\Cigar\InputOption
 */
class InputOptionTest extends TestCase
{
    #[Test]
    public function it_returns_the_full_short_code_when_a_value_is_required(): void
    {
        $inputOption = InputOption::create('long-code', 'l', InputOption::VALUE_REQUIRED);

        $this->assertSame('l:', $inputOption->fullShortCode());
    }

    #[Test]
    public function it_returns_the_full_long_code_when_a_value_is_required(): void
    {
        $inputOption = InputOption::create('long-code', 'l', InputOption::VALUE_REQUIRED);

        $this->assertSame('long-code:', $inputOption->fullLongCode());
    }

    #[Test]
    public function it_returns_the_full_short_code_when_a_value_is_not_required(): void
    {
        $inputOption = InputOption::create('long-code', 'l');

        $this->assertSame('l', $inputOption->fullShortCode());
    }

    #[Test]
    public function it_returns_the_full_long_code_when_a_value_is_not_required(): void
    {
        $inputOption = InputOption::create('long-code', 'l');

        $this->assertSame('long-code', $inputOption->fullLongCode());
    }

    #[Test]
    #[DataProvider('value_required_or_not')]
    public function it_returns_if_value_is_required(int $valueMode, bool $expectedReturn): void
    {
        $inputOption = InputOption::create('long-code', 'l', $valueMode);
        $this->assertSame($expectedReturn, $inputOption->valueIsRequired());
    }

    public static function value_required_or_not(): array
    {
        return [
            [InputOption::VALUE_NONE, false],
            [InputOption::VALUE_REQUIRED, true],
        ];
    }
}
