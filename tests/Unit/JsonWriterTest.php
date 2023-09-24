<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\JsonWriter;
use Brunty\Cigar\Result;
use Brunty\Cigar\Url;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brunty\Cigar\JsonWriter
 * @uses \Brunty\Cigar\Result
 * @uses \Brunty\Cigar\Url
 */
class JsonWriterTest extends TestCase
{
    private JsonWriter $writer;

    protected function setUp(): void
    {
        $this->writer = new JsonWriter();
    }

    #[Test]
    public function it_writes_an_error_line(): void
    {
        $expected = <<<JSON
{"type":"error","message":"problem"}

JSON;
        ob_start();
        $this->writer->writeErrorLine('problem');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertSame($expected, $output);
    }

    #[Test]
    public function it_writes_results(): void
    {
        $results = [
            new Result(new Url('url', 201, 'c', 't'), 200, 'c', 't'),
            new Result(new Url('url', 200, 'c', 't'), 200, 'c', 't'),
            new Result(new Url('url', 200, 'c', 't'), 200, 'c', 't'),
        ];

        $expected = <<<JSON
{"type":"results","time_taken":0.5,"passed":false,"results_count":3,"results_passed_count":2,"results":[{"passed":false,"url":"url","status_code_expected":201,"status_code_actual":200,"content_type_expected":"t","content_type_actual":"t","content_expected":"c"},{"passed":true,"url":"url","status_code_expected":200,"status_code_actual":200,"content_type_expected":"t","content_type_actual":"t","content_expected":"c"},{"passed":true,"url":"url","status_code_expected":200,"status_code_actual":200,"content_type_expected":"t","content_type_actual":"t","content_expected":"c"}]}

JSON;

        ob_start();
        $this->writer->writeResults(2, 3, false, 0.5, ...$results);
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertSame($expected, $output);
    }
}
