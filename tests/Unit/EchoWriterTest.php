<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\EchoWriter;
use Brunty\Cigar\Result;
use Brunty\Cigar\Url;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brunty\Cigar\EchoWriter
 * @uses \Brunty\Cigar\Result
 * @uses \Brunty\Cigar\Url
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
\033[31merror\033[0m

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
        $results = [
            new Result(new Url('url1', 201, 'c', 't'), 200, 'c', 't'),
            new Result(new Url('url2', 200, 'c', 't'), 200, 'c', 't'),
            new Result(new Url('url3', 200, ''), 200, '', ''),
        ];

        $expected = <<<CONTENT
\033[31m✘ url1 [201:200] [t:t] c\033[0m
\033[32m✓ url2 [200:200] [t:t] c\033[0m
\033[32m✓ url3 [200:200] \033[0m

[\033[31m2/3\033[0m] passed in 0.5s


CONTENT;

        ob_start();
        $this->writer->writeResults(2, 3, false, 0.5, ...$results);
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertSame($expected, $output);
    }
}
