<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\Output;
use Brunty\Cigar\Results;
use Brunty\Cigar\SystemTimer;
use Brunty\Cigar\Writer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{
    #[Test]
    public function it_outputs_an_error_line(): void
    {
        $writer = new class implements Writer {
            public string $errorMessage = '';

            public function writeErrorLine(string $message): void
            {
                $this->errorMessage .= $message;
            }

            public function writeResults(Results $results, float $timeDiff): void
            {
            }
        };

        $output = new Output($writer, new SystemTimer());
        $output->writeErrorLine('message-here');

        $this->assertSame('message-here', $writer->errorMessage);
    }

    #[Test]
    public function it_outputs_results(): void
    {
        $writer = new class implements Writer {
            public string $errorMessage = '';

            public function writeErrorLine(string $message): void
            {
                $this->errorMessage .= $message;
            }

            public function writeResults(Results $results, float $timeDiff): void
            {
            }
        };

        $output = new Output($writer, new SystemTimer());
        $output->writeErrorLine('message-here');

        $this->assertSame('message-here', $writer->errorMessage);
    }
}
