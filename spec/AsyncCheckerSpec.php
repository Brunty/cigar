<?php

use Brunty\Cigar\AsyncChecker;
use Brunty\Cigar\Domain;
use Brunty\Cigar\Result;

describe('AsyncChecker', function() {
    it('checks a domain', function() {
        $domain = new Domain('http://httpbin.org/status/200', 200);
        $domains = [$domain];

        $results = (new AsyncChecker)->check($domains);

        $expected = [
            new Result($domain, 200)
        ];

        expect($results)->toEqual($expected);
    });

    it('checks more than one domain', function() {

        $domains = [
            new Domain('http://httpbin.org/status/200', 200),
            new Domain('http://httpbin.org/status/500', 500),
            new Domain('http://httpbin.org/status/404', 404),
        ];

        $results = (new AsyncChecker)->check($domains);

        $expected = array_map(function(Domain $domain){
            return new Result($domain, $domain->getStatus());
        }, $domains);

        expect($results)->toEqual($expected);
    });
});
