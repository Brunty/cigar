<?php

namespace Brunty\Cigar\Tests;

use Brunty\Cigar\Checker;
use Brunty\Cigar\Domain;
use Brunty\Cigar\Result;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    /**
     * @test
     */
    public function it_checks_a_domain()
    {
        $domain = new Domain('http://httpbin.org/status/200', 200);
        $domains = [$domain];

        $results = (new Checker)->check($domains);

        $expected = [
            new Result($domain, 200)
        ];

        self::assertEquals($expected, $results);
    }

    /**
     * @test
     */
    public function it_passes_if_status_codes_match()
    {
        $domain = new Domain('http://httpbin.org/status/200', 200);
        $result = new Result($domain, 200);

        self::assertTrue($result->passed());
    }

    /**
     * @test
     */
    public function it_passes_if_the_response_contains_matching_content()
    {
        $domain = new Domain('http://httpbin.org/status/200', 200, 'foobar');
        $result = new Result($domain, 200, '<h1>foobar</h1>');

        self::assertTrue($result->passed());
    }
}
