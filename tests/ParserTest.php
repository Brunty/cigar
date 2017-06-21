<?php

namespace Brunty\Cigar\Tests;

use Brunty\Cigar\Domain;
use Brunty\Cigar\Parser;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @test
     */
    public function it_parses_a_file_that_is_correctly_formatted()
    {
        $structure = [
            '.cigar.json' => '[
  {
    "url": "http://httpbin.org/status/418",
    "status": 418
  },
  {
    "url": "http://httpbin.org/status/200",
    "status": 200
  },
  {
    "url": "http://httpbin.org/status/418",
    "status": 418,
    "content": "teapot"
  }
]
'
        ];
        $root = vfsStream::setup('root', null, $structure);

        $results = (new Parser)->parse('vfs://root/.cigar.json');

        $expected = [
            new Domain('http://httpbin.org/status/418', 418),
            new Domain('http://httpbin.org/status/200', 200),
            new Domain('http://httpbin.org/status/418', 418, 'teapot'),
        ];

        self::assertEquals($expected, $results);
    }

    /**
     * @test
     */
    public function it_lets_errors_be_thrown_on_parsing_a_file()
    {
        $this->expectException(\ParseError::class);
        $structure = [
            '.cigar.json' => 'http://httpbin.org/status/418'
        ];
        $root = vfsStream::setup('root', null, $structure);

        $results = (new Parser)->parse('vfs://root/.cigar.json');
    }
}
