<?php

use Brunty\Cigar\Url;
use Brunty\Cigar\Parser;
use org\bovigo\vfs\vfsStream;

describe('Parser', function () {
    it('parses a file that is correctly formatted', function () {
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
',
        ];
        vfsStream::setup('root', null, $structure);

        $results = (new Parser)->parse('vfs://root/.cigar.json');

        $expected = [
            new Url('http://httpbin.org/status/418', 418),
            new Url('http://httpbin.org/status/200', 200),
            new Url('http://httpbin.org/status/418', 418, 'teapot'),
        ];

        expect($results)->toEqual($expected);
    });

    it('lets errors be thrown on parsing a file', function () {
        $structure = [
            '.cigar.json' => 'http://httpbin.org/status/418',
        ];
        vfsStream::setup('root', null, $structure);

        $fn = function () {
            (new Parser)->parse('vfs://root/.cigar.json');
        };

        expect($fn)->toThrow(new ParseError('Could not parse vfs://root/.cigar.json'));
    });
});
