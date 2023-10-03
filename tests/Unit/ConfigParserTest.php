<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\ConfigParser;
use Brunty\Cigar\Url;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ParseError;
use org\bovigo\vfs\vfsStream;

/**
 * @covers \Brunty\Cigar\ConfigParser
 * @uses   \Brunty\Cigar\Url
 */
class ConfigParserTest extends TestCase
{
    #[Test]
    public function it_parses_a_correctly_formatted_config_file_and_casts_values(): void
    {
        $structure = [
            'cigar.json' => '[
  {
    "url": "http://httpbin.org/status/200",
    "status": 200
  },
  {
    "url": "http://httpbin.org/status/418",
    "status": 418,
    "content": "teapot",
    "content-type": "kitchen/teapot",
    "connect-timeout": 1,
    "timeout": 2
  },
  {
    "url": "http://httpbin.org/status/200",
    "status": 200,
    "content": 1,
    "content-type": 2,
    "connect-timeout": "3",
    "timeout": "4"
  }
]
',
        ];
        vfsStream::setup('root', null, $structure);

        $results = (new ConfigParser())->parse('vfs://root/cigar.json');

        $expected = [
            new Url('http://httpbin.org/status/200', 200),
            new Url('http://httpbin.org/status/418', 418, 'teapot', 'kitchen/teapot', 1, 2),
            new Url('http://httpbin.org/status/200', 200, '1', '2', 3, 4),
        ];

        $this->assertEquals($expected, $results);
    }

    #[Test]
    public function it_parses_a_file_that_contains_both_absolute_and_relative_urls(): void
    {
        $structure = [
            'cigar.json' => '[
  {
    "url": "/status/418",
    "status": 418
  },
  {
    "url": "status/200",
    "status": "200"
  },
  {
    "url": "http://httpbin.org/status/418",
    "status": 418,
    "content": "teapot"
  }
]
',
        ];
        vfsStream::setup('root', null, $structure);

        $results = (new ConfigParser('http://httpbin.org/'))->parse('vfs://root/cigar.json');

        $expected = [
            new Url('http://httpbin.org/status/418', 418),
            new Url('http://httpbin.org/status/200', 200),
            new Url('http://httpbin.org/status/418', 418, 'teapot'),
        ];

        $this->assertEquals($expected, $results);
    }

    #[Test]
    public function it_throws_an_exception_if_a_url_cannot_be_parsed(): void
    {
        $this->expectException(ParseError::class);
        $this->expectExceptionMessage('Could not parse URL: http://:80');
        $structure = [
            'cigar.json' => '[
  {
    "url": "http://:80",
    "status": 418,
    "content": "teapot"
  }
]
',
        ];
        vfsStream::setup('root', null, $structure);

        (new ConfigParser())->parse('vfs://root/cigar.json');
    }

    #[Test]
    public function it_lets_errors_be_thrown_on_parsing_a_file(): void
    {
        $this->expectException(ParseError::class);
        $this->expectExceptionMessage('Could not parse vfs://root/cigar.json');
        $structure = [
            'cigar.json' => 'http://httpbin.org/status/418',
        ];
        vfsStream::setup('root', null, $structure);

        (new ConfigParser())->parse('vfs://root/cigar.json');
    }
}
