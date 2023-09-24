<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\InputOption;
use Brunty\Cigar\InputOptions;
use Brunty\Cigar\Output;
use Brunty\Cigar\Results;
use Brunty\Cigar\SystemTimer;
use Brunty\Cigar\Timer;
use Brunty\Cigar\Writer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brunty\Cigar\Output
 * @uses   \Brunty\Cigar\SystemTimer
 * @uses   \Brunty\Cigar\InputOptions
 * @uses   \Brunty\Cigar\InputOption
 * @uses   \Brunty\Cigar\Results
 */
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
            public Results $results;

            public float $timeDiff = 0;

            public function writeErrorLine(string $message): void
            {
            }

            public function writeResults(Results $results, float $timeDiff): void
            {
                $this->results = $results;
                $this->timeDiff = $timeDiff;
            }
        };

        $timer = new class implements Timer {
            public function start(): float
            {
                return 0.5;
            }

            public function stop(): float
            {
                return 2.63456;
            }
        };

        $output = new Output($writer, $timer);
        $results = new Results();
        $output->outputResults($results, 0.5);

        $this->assertSame(2.135, $writer->timeDiff);
    }

    #[Test]
    public function it_generates_help_output(): void
    {
        $writer = new class implements Writer {
            public function writeErrorLine(string $message): void
            {
            }

            public function writeResults(Results $results, float $timeDiff): void
            {
            }
        };

        $output = new Output($writer, new SystemTimer());

        $inputOptions = new InputOptions([
            'help' => InputOption::create('help', '', InputOption::VALUE_NONE, 'Show the help message'),
            'version' => InputOption::create('version', 'v', InputOption::VALUE_NONE, 'Print the version of Cigar'),
            'config' => InputOption::create('config', 'f', InputOption::VALUE_REQUIRED, 'Use the specified config'),
            'url' => InputOption::create('url', '', InputOption::VALUE_REQUIRED, 'Something with a URL'),
        ]);
        $helpText = $output->generateHelpOutputForOptions($inputOptions);
        file_put_contents('test', $helpText);
        $expectedHelpText = <<<HELP
\033[33mOptions:\033[0m
  \033[32m          --help        \033[0m  Show the help message
  \033[32m-v        --version     \033[0m  Print the version of Cigar
  \033[32m-f VALUE  --config=VALUE\033[0m  Use the specified config
  \033[32m          --url=VALUE   \033[0m  Something with a URL

HELP;

        $this->assertSame($expectedHelpText, $helpText);
    }
}
