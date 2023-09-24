<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\Result;
use Brunty\Cigar\Results;
use Brunty\Cigar\Url;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brunty\Cigar\Results
 * @uses \Brunty\Cigar\Result
 * @uses \Brunty\Cigar\Url
 */
class ResultsTest extends TestCase
{
    #[Test]
    public function it_returns_the_number_of_passed_results(): void
    {
        $results = new Results(
            new Result(new Url('url', 200), 201),
            new Result(new Url('url', 200), 201),
            new Result(new Url('url', 200), 200),
        );

        $this->assertSame(1, $results->numberOfPassedResults());
        $this->assertSame(1, $results->numberOfPassedResults());
    }

    #[Test]
    public function it_returns_the_total_number_of_results(): void
    {
        $results = new Results(
            new Result(new Url('url', 200), 201),
            new Result(new Url('url', 200), 201),
            new Result(new Url('url', 200), 200),
        );

        $this->assertSame(3, $results->totalNumberOfResults());
    }

    #[Test]
    public function it_returns_true_if_the_total_results_equals_passed_results(): void
    {
        $results = new Results(
            new Result(new Url('url', 200), 200),
            new Result(new Url('url', 200), 200),
            new Result(new Url('url', 200), 200),
        );

        $this->assertTrue($results->hasPassed());
    }

    #[Test]
    public function it_returns_false_if_the_total_results_does_not_equal_passed_results(): void
    {
        $results = new Results(
            new Result(new Url('url', 200), 201),
            new Result(new Url('url', 200), 200),
            new Result(new Url('url', 200), 200),
        );

        $this->assertFalse($results->hasPassed());
    }
}
