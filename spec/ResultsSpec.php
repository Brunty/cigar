<?php

declare(strict_types=1);

use Brunty\Cigar\Result;
use Brunty\Cigar\Url;

describe('Result', function (): void {
    it('passes if status codes match', function (): void {
        $domain = new Url('http://httpbin.org/status/200', 200);
        $result = new Result($domain, 200);

        expect($result->hasPassed())->toBe(true);
    });

    it('fails if status codes do not match', function (): void {
        $domain = new Url('http://httpbin.org/status/200', 200);
        $result = new Result($domain, 201);

        expect($result->hasPassed())->toBe(false);
    });

    it('passes if the response contains matching content', function (): void {
        $domain = new Url('http://httpbin.org/status/200', 200, 'foobar');
        $result = new Result($domain, 200, '<h1>foobar</h1>');
        expect($result->hasPassed())->toBe(true);
    });

    it('fails if the response does not contain matching content', function (): void {
        $domain = new Url('http://httpbin.org/status/200', 200, 'foobar');
        $result = new Result($domain, 200, '<h1>hi there</h1>');
        expect($result->hasPassed())->toBe(false);
    });

    it('returns the domain and status code', function (): void {
        $domain = new Url('http://httpbin.org/status/200', 200, 'foobar');
        $result = new Result($domain, 200, '<h1>hi there</h1>');

        expect($result->url)->toBe($domain);
        expect($result->statusCode)->toBe(200);
    });
});
