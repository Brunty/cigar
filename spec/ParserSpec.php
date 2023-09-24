<?php

declare(strict_types=1);

use Brunty\Cigar\ConfigParser;
use Brunty\Cigar\Url;
use org\bovigo\vfs\vfsStream;

describe('ConfigParser', function (): void {
    it('parses a file that is correctly formatted', function (): void {
        $structure = [
            'cigar.json' => '[
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
    "content": "teapot",
    "content-type": "kitchen/teapot",
    "connect-timeout": 1,
    "timeout": 2
  }
]
',
        ];
        vfsStream::setup('root', null, $structure);

        $results = (new ConfigParser())->parse('vfs://root/cigar.json');

        $expected = [
            new Url('http://httpbin.org/status/418', 418),
            new Url('http://httpbin.org/status/200', 200),
            new Url('http://httpbin.org/status/418', 418, 'teapot', 'kitchen/teapot', 1, 2),
        ];

        expect($results)->toEqual($expected);
    });

    it('lets errors be thrown on parsing a file', function (): void {
        $structure = [
            'cigar.json' => 'http://httpbin.org/status/418',
        ];
        vfsStream::setup('root', null, $structure);

        $fn = function (): void {
            (new ConfigParser())->parse('vfs://root/cigar.json');
        };

        expect($fn)->toThrow(new ParseError('Could not parse vfs://root/cigar.json'));
    });

    it('parses a file that contains both relative and absolute URLs', function (): void {
        $structure = [
            'cigar.json' => '[
  {
    "url": "/status/418",
    "status": 418
  },
  {
    "url": "status/200",
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

        $results = (new ConfigParser('http://httpbin.org'))->parse('vfs://root/cigar.json');

        $expected = [
            new Url('http://httpbin.org/status/418', 418),
            new Url('http://httpbin.org/status/200', 200),
            new Url('http://httpbin.org/status/418', 418, 'teapot'),
        ];

        expect($results)->toEqual($expected);
    });

    it('throws an exception if a URL cannot be parsed', function (): void {
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


        $fn = function (): void {
            (new ConfigParser())->parse('vfs://root/cigar.json');
        };

        expect($fn)->toThrow(new ParseError('Could not parse URL: http://:80'));
    });
});
