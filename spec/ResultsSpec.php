<?php

use Brunty\Cigar\Domain;
use Brunty\Cigar\Result;

describe('Result', function() {
    it('passes if status codes match', function() {
        $domain = new Domain('http://httpbin.org/status/200', 200);
        $result = new Result($domain, 200);

        expect($result->passed())->toBe(true);
    });

    it('fails if status codes do not match', function() {
        $domain = new Domain('http://httpbin.org/status/200', 200);
        $result = new Result($domain, 201);

        expect($result->passed())->toBe(false);
    });

    it('passes if the response contains matching content', function() {
        $domain = new Domain('http://httpbin.org/status/200', 200, 'foobar');
        $result = new Result($domain, 200, '<h1>foobar</h1>');
        expect($result->passed())->toBe(true);
    });

    it('fails if the response does not contain matching content', function() {
        $domain = new Domain('http://httpbin.org/status/200', 200, 'foobar');
        $result = new Result($domain, 200, '<h1>hi there</h1>');
        expect($result->passed())->toBe(false);
    });

    it('returns the domain and status code', function() {
        $domain = new Domain('http://httpbin.org/status/200', 200, 'foobar');
        $result = new Result($domain, 200, '<h1>hi there</h1>');

        expect($result->getDomain())->toBe($domain);
        expect($result->getStatusCode())->toBe(200);
    });
});
