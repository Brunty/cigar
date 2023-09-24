<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\InputOption;
use Brunty\Cigar\InputOptions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brunty\Cigar\InputOptions
 * @uses \Brunty\Cigar\InputOption
 */
class InputOptionsTest extends TestCase
{
    #[Test]
    public function it_constructs_the_lists_of_short_and_long_codes(): void
    {
        $inputOptions = new InputOptions([
            'help' => InputOption::create('help', 'h'),
            'file' => InputOption::create('file', 'f', InputOption::VALUE_REQUIRED),
        ]);

        $this->assertSame('f:h', $inputOptions->shortCodes);
        $this->assertSame(['file:', 'help'], $inputOptions->longCodes);
    }
}
