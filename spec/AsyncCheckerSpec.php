<?php

use Brunty\Cigar\AsyncChecker;
use Brunty\Cigar\Url;
use Brunty\Cigar\Result;

describe('AsyncChecker', function() {
    it('checks a domain', function() {
        $domain = new Url('http://httpbin.org/status/200', 200);
        $domains = [$domain];

        $results = (new AsyncChecker)->check($domains);

        $expected = [
            new Result($domain, 200)
        ];

        expect($results)->toEqual($expected);
    });

    it('checks more than one domain', function() {

        $domains = [
            new Url('http://httpbin.org/status/200', 200),
            new Url('http://httpbin.org/status/500', 500),
            new Url('http://httpbin.org/status/404', 404),
        ];

        $results = (new AsyncChecker)->check($domains);

        $expected = array_map(function(Url $domain){
            return new Result($domain, $domain->getStatus());
        }, $domains);

        expect($results)->toEqual($expected);
    });
});
