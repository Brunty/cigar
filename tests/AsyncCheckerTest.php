<?php

namespace Brunty\Cigar\Tests;

use Brunty\Cigar\AsyncChecker;
use Brunty\Cigar\Domain;
use Brunty\Cigar\Result;
use PHPUnit\Framework\TestCase;

class AsyncCheckerTest extends TestCase
{
    /**
     * @test
     */
    public function it_checks_a_domain()
    {
        $domain = new Domain('http://httpbin.org/status/200', 200);
        $domains = [$domain];

        $results = (new AsyncChecker)->check($domains);

        $expected = [
            new Result($domain, 200)
        ];

        self::assertEquals($expected, $results);
    }

    /**
     * @test
     */
    public function it_checks_more_than_one_domain()
    {
        $domains = [
            new Domain('http://httpbin.org/status/200', 200),
            new Domain('http://httpbin.org/status/500', 500),
            new Domain('http://httpbin.org/status/404', 404),
        ];

        $results = (new AsyncChecker)->check($domains);

        $expected = array_map(function(Domain $domain){
            return new Result($domain, $domain->getStatus());
        }, $domains);

        self::assertEquals($expected, $results);
    }
}
