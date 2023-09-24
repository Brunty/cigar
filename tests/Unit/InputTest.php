<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\Input;
use Brunty\Cigar\InputOption;
use Brunty\Cigar\InputOptions;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brunty\Cigar\Input
 * @uses   \Brunty\Cigar\InputOptions
 * @uses   \Brunty\Cigar\InputOption
 */
class InputTest extends TestCase
{
    #[Test]
    public function it_throws_an_exception_if_the_option_was_not_configured(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find option with my-option');

        $input = new Input(
            new InputOptions([
                'help' => InputOption::create('help', '', InputOption::VALUE_NONE),
            ]),
            [],
        );

        $input->getOption('my-option');
    }

    #[Test]
    public function it_returns_false_if_a_no_value_option_was_not_submitted_with_a_short_code(): void
    {
        $input = new Input(
            new InputOptions([
                'help' => InputOption::create('help', 'h'),
            ]),
            [],
        );

        $this->assertFalse($input->getOption('help'));
    }

    #[Test]
    public function it_returns_true_if_a_no_value_option_was_submitted_with_a_short_code(): void
    {
        $input = new Input(
            new InputOptions([
                'help' => InputOption::create('help', 'h'),
            ]),
            ['h' => false],
        );

        $this->assertTrue($input->getOption('help'));
    }

    #[Test]
    public function it_returns_the_default_value_if_a_required_value_option_was_not_submitted_with_a_short_code(): void
    {
        $input = new Input(
            new InputOptions([
                'file' => InputOption::create('file', 'f', InputOption::VALUE_REQUIRED, 'description', 'default-value'),
            ]),
            [],
        );

        $this->assertSame('default-value', $input->getOption('file'));
    }

    #[Test]
    public function it_returns_the_default_value_if_a_required_value_option_was_submitted_with_no_value_with_a_short_code(): void
    {
        $input = new Input(
            new InputOptions([
                'file' => InputOption::create('file', 'f', InputOption::VALUE_REQUIRED, 'description', 'default-value'),
            ]),
            ['f' => false], // getopt() wouldn't even put this in the array, but this is just to be safe
        );

        $this->assertSame('default-value', $input->getOption('file'));
    }

    #[Test]
    public function it_returns_the_value_if_a_required_value_option_was_submitted_with_a_value_with_a_short_code(): void
    {
        $input = new Input(
            new InputOptions([
                'file' => InputOption::create('file', 'f', InputOption::VALUE_REQUIRED, 'description', 'default-value'),
            ]),
            ['f' => 'bar'],
        );

        $this->assertSame('bar', $input->getOption('file'));
    }

    #[Test]
    public function it_returns_true_if_a_no_value_option_was_submitted_with_a_long_code(): void
    {
        $input = new Input(
            new InputOptions([
                'help' => InputOption::create('help', 'h'),
            ]),
            ['help' => false],
        );

        $this->assertTrue($input->getOption('help'));
    }

    #[Test]
    public function it_returns_the_default_value_if_a_required_value_option_was_submitted_with_no_value_with_a_long_code(): void
    {
        $input = new Input(
            new InputOptions([
                'file' => InputOption::create('file', 'f', InputOption::VALUE_REQUIRED, 'description', 'default-value'),
            ]),
            ['file' => false], // getopt() wouldn't even put this in the array, but this is just to be safe
        );

        $this->assertSame('default-value', $input->getOption('file'));
    }

    #[Test]
    public function it_returns_the_value_if_a_required_value_option_was_submitted_with_a_value_with_a_long_code(): void
    {
        $input = new Input(
            new InputOptions([
                'file' => InputOption::create('file', 'f', InputOption::VALUE_REQUIRED, 'description', 'default-value'),
            ]),
            ['file' => 'bar'],
        );

        $this->assertSame('bar', $input->getOption('file'));
    }
}
