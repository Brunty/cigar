<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\EchoWriter;
use Brunty\Cigar\Result;
use Brunty\Cigar\Results;
use Brunty\Cigar\Url;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brunty\Cigar\EchoWriter
 * @uses   \Brunty\Cigar\ConsoleColours
 * @uses   \Brunty\Cigar\Results
 * @uses   \Brunty\Cigar\Result
 * @uses   \Brunty\Cigar\Url
 */
class EchoWriterTest extends TestCase
{
    private EchoWriter $writer;

    protected function setUp(): void
    {
        $this->writer = new EchoWriter();
    }

    #[Test]
    public function it_writes_an_error_line(): void
    {
        $expected = <<<CONTENT
\e[31merror\e[0m

CONTENT;
        ob_start();
        $this->writer->writeErrorLine('error');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertSame($expected, $output);
    }

    #[Test]
    public function it_writes_results(): void
    {
        $results = new Results(
            new Result(new Url('url1', 201, 'c', 't'), 200, 'c', 't'),
            new Result(new Url('url2', 200, 'c', 't'), 200, 'c', 't'),
            new Result(new Url('url3', 200, ''), 200, '', ''),
        );

        $expected = <<<CONTENT
\e[31m✘ url1 [201:200] [t:t] c\e[0m
\e[32m✓ url2 [200:200] [t:t] c\e[0m
\e[32m✓ url3 [200:200] \e[0m

[\e[31m2/3\e[0m] passed in 0.5s


CONTENT;

        ob_start();
        $this->writer->writeResults($results, 0.5);
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertSame($expected, $output);
    }
}
