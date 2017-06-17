<?php

namespace Brunty\Cigar\Tests;

use Brunty\Cigar\Checker;
use Brunty\Cigar\Domain;
use Brunty\Cigar\Parser;
use Brunty\Cigar\Result;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class CheckerTest extends TestCase
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
}
