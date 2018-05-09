<?php

use Brunty\Cigar\Outputter;
use Brunty\Cigar\Result;

describe('Outputter', function () {
    beforeEach(function() {
        $this->domain = new \Brunty\Cigar\Url('url', 418, 'teapot', 'teapot');
        $this->results = [
            new \Brunty\Cigar\Result($this->domain, 418, 'teapot', 'teapot'),
            new \Brunty\Cigar\Result($this->domain, 419),
        ];
        $this->passedResults = array_filter($this->results, function (Result $result) {
            return $result->hasPassed();
        });
    });

    it('outputs an error line', function () {
        $fn = function () {
            (new Outputter)->writeErrorLine('Error message');
        };

        expect($fn)->toEcho("\033[31mError message\033[0m\n");
    });

    it('does not output an error line when run quietly', function () {
        $fn = function () {
            (new Outputter($quiet = true))->writeErrorLine('Error message');
        };

        expect($fn)->toEcho('');
    });

    it('outputs results if some results have passed', function () {
        $results = $this->results;
        $passedResults = $this->passedResults;

        allow('microtime')->toBeCalled()->andReturn(3);

        $fn = function () use ($passedResults, $results) {
            (new Outputter)->outputResults($passedResults, $results, 1.5);
        };

        $output = <<< OUTPUT
\e[32m✓ url [418:418] [teapot:teapot] teapot\e[0m
\e[31m✘ url [418:419] [teapot:] teapot\e[0m

[\e[31m1/2\e[0m] passed in 1.5s


OUTPUT;

        expect($fn)->toEcho($output);
    });

    it('outputs results quietly', function () {
        $results = $this->results;
        $passedResults = $this->passedResults;

        $fn = function () use ($passedResults, $results) {
            (new Outputter(true))->outputResults($passedResults, $results, 1.5);
        };

        expect($fn)->toEcho('');
    });
});
