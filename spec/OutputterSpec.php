<?php

use Brunty\Cigar\Outputter;

describe('Outputter', function() {
    it('outputs an error line', function() {
        $fn = function () {
            (new Outputter)->writeErrorLine('Error message');
        };

        expect($fn)->toEcho("\033[31mError message\033[0m\n");
    });

    it('does not output an error line when run quietly', function() {
        $fn = function () {
            (new Outputter(true))->writeErrorLine('Error message');
        };

        expect($fn)->toEcho('');
    });

    it('outputs results', function() {
        $domain = new \Brunty\Cigar\Url('url', 418, 'teapot');
        $results = [
            new \Brunty\Cigar\Result($domain, 418, 'teapot'),
            new \Brunty\Cigar\Result($domain, 419),
        ];

        $fn = function() use ($results) {
            (new Outputter)->outputResults($results);
        };

        $output = <<< OUTPUT
\e[32m✓ url [418:418] teapot\e[0m
\e[31m✘ url [418:419] teapot\e[0m

OUTPUT;

        expect($fn)->toEcho($output);
    });

    it('outputs results quietly', function() {
        $domain = new \Brunty\Cigar\Url('url', 418, 'teapot');
        $results = [
            new \Brunty\Cigar\Result($domain, 418, 'teapot'),
            new \Brunty\Cigar\Result($domain, 419),
        ];

        $fn = function() use ($results) {
            (new Outputter(true))->outputResults($results);
        };

        expect($fn)->toEcho('');
    });

    it('outputs the stats of the execution if all results have passed', function() {
        $domain = new \Brunty\Cigar\Url('url', 418, 'teapot');
        $results = [
            new \Brunty\Cigar\Result($domain, 418, 'teapot'),
            new \Brunty\Cigar\Result($domain, 419),
        ];
        $passedResults = $results;

        allow('microtime')->toBeCalled()->andReturn(2.5);

        $fn = function() use ($results, $passedResults) {
            (new Outputter)->outputStats($passedResults, $results, 1.0);
        };

        $output = PHP_EOL . "[\033[32m2/2\033[0m] passed in 1.5s" . PHP_EOL . PHP_EOL;

        expect($fn)->toEcho($output);
    });

    it('outputs the stats of the execution if some URLs have failed', function() {
        $domain = new \Brunty\Cigar\Url('url', 418, 'teapot');
        $results = [
            new \Brunty\Cigar\Result($domain, 418, 'teapot'),
            new \Brunty\Cigar\Result($domain, 419),
        ];
        $passedResults = [
            new \Brunty\Cigar\Result($domain, 418, 'teapot')
        ];

        allow('microtime')->toBeCalled()->andReturn(2.5);

        $fn = function() use ($results, $passedResults) {
            (new Outputter)->outputStats($passedResults, $results, 1.0);
        };

        $output = PHP_EOL . "[\033[31m1/2\033[0m] passed in 1.5s" . PHP_EOL . PHP_EOL;

        expect($fn)->toEcho($output);
    });


    it('does not output stats when run quietly', function() {
        $domain = new \Brunty\Cigar\Url('url', 418, 'teapot');
        $results = [
            new \Brunty\Cigar\Result($domain, 418, 'teapot'),
            new \Brunty\Cigar\Result($domain, 419),
        ];
        $passedResults = [
            new \Brunty\Cigar\Result($domain, 418, 'teapot')
        ];

        allow('microtime')->toBeCalled()->andReturn(2.5);

        $fn = function() use ($results, $passedResults) {
            (new Outputter(true))->outputStats($passedResults, $results, 1.0);
        };

        expect($fn)->toEcho('');
    });
});
