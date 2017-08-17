<?php

use Brunty\Cigar\Outputter;

describe('Outputter', function () {
    beforeEach(function() {
        $this->domain = new \Brunty\Cigar\Url('url', 418, 'teapot');
        $this->results = [
            new \Brunty\Cigar\Result($this->domain, 418, 'teapot'),
            new \Brunty\Cigar\Result($this->domain, 419),
        ];
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

    it('outputs results', function () {
        $results = $this->results;

        $fn = function () use ($results) {
            (new Outputter)->outputResults($results);
        };

        $output = <<< OUTPUT
\e[32m✓ url [418:418] teapot\e[0m
\e[31m✘ url [418:419] teapot\e[0m

OUTPUT;

        expect($fn)->toEcho($output);
    });

    it('outputs results quietly', function () {
        $results = $this->results;

        $fn = function () use ($results) {
            (new Outputter(true))->outputResults($results);
        };

        expect($fn)->toEcho('');
    });

    it('outputs the stats of the execution if all results have passed', function () {
        $results = $passedResults = $this->results;

        allow('microtime')->toBeCalled()->andReturn(2.5);

        $fn = function () use ($results, $passedResults) {
            (new Outputter)->outputStats($passedResults, $results, 1.0);
        };

        expect($fn)->toEcho(PHP_EOL . "[\033[32m2/2\033[0m] passed in 1.5s" . PHP_EOL . PHP_EOL);
    });

    it('outputs the stats of the execution if some URLs have failed', function () {
        $results = $this->results;
        $passedResults = [
            new \Brunty\Cigar\Result($this->domain, 418, 'teapot'),
        ];

        allow('microtime')->toBeCalled()->andReturn(2.5);

        $fn = function () use ($results, $passedResults) {
            (new Outputter)->outputStats($passedResults, $results, 1.0);
        };

        $output = PHP_EOL . "[\033[31m1/2\033[0m] passed in 1.5s" . PHP_EOL . PHP_EOL;

        expect($fn)->toEcho($output);
    });


    it('does not output stats when run quietly', function () {
        $results = $this->results;
        $passedResults = [
            new \Brunty\Cigar\Result($this->domain, 418, 'teapot'),
        ];

        allow('microtime')->toBeCalled()->andReturn(2.5);

        $fn = function () use ($results, $passedResults) {
            (new Outputter(true))->outputStats($passedResults, $results, 1.0);
        };

        expect($fn)->toEcho('');
    });
});
