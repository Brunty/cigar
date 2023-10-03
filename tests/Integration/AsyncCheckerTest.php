<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Integration;

use Brunty\Cigar\AsyncChecker;
use Brunty\Cigar\Url;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brunty\Cigar\AsyncChecker
 * @uses \Brunty\Cigar\Results
 * @uses \Brunty\Cigar\Result
 * @uses \Brunty\Cigar\Url
 */
class AsyncCheckerTest extends TestCase
{
    #[Test]
    public function it_checks_urls(): void
    {
        $urls = [
            new Url('http://httpbin.org/status/418', 418, 'teapot'),
            new Url('http://httpbin.org/status/200', 200, '', 'text/html'),
            new Url('http://httpbin.org/status/304', 304),
            new Url('http://httpbin.org/status/500', 501),
            new Url('http://httpbin.org/delay/3', 200, '', '', 1, 1),
        ];

        $checker = new AsyncChecker();

        $results = $checker->check($urls);

        $this->assertFalse($results->hasPassed());
        $this->assertEquals(5, $results->totalNumberOfResults());
        $this->assertEquals(3, $results->numberOfPassedResults());
    }

    #[Test]
    public function it_checks_urls_with_authorization(): void
    {
        $urls = [
            new Url('http://httpbin.org/headers', 200, 'some-random-string'),
        ];

        $checker = new AsyncChecker(true, 'some-random-string');

        $results = $checker->check($urls);

        $this->assertTrue($results->hasPassed());
    }

    #[Test]
    public function it_checks_urls_with_headers_set(): void
    {
        $urls = [
            new Url('http://httpbin.org/headers', 200, 'Header-Foo'),
        ];

        $checker = new AsyncChecker(true, null, ['Header-Foo: Content-Bar']);

        $results = $checker->check($urls);

        $this->assertTrue($results->hasPassed());
    }

    #[Test]
    public function it_checks_urls_with_insecure_ssl(): void
    {
        $urls = [
            new Url('https://cigar-do-not-work.apps.brunty.me', 403, ''),
        ];

        $checker = new AsyncChecker(false);

        $results = $checker->check($urls);

        $this->assertTrue($results->hasPassed());
    }

    #[Test]
    public function it_checks_urls_with_insecure_ssl_fail(): void
    {
        $urls = [
            new Url('https://cigar-do-not-work.apps.brunty.me', 403, ''),
        ];

        $checker = new AsyncChecker();

        $results = $checker->check($urls);

        $this->assertFalse($results->hasPassed());
    }
}
