<?php

use Brunty\Cigar\Outputter;

describe('Outputter', function() {
    it('outputs an error line', function() {
        $fn = function () {
            Outputter::writeErrorLine('Error message');
        };

        expect($fn)->toEcho("\033[31mError message\033[0m\n");
    });

    it('outputs results', function() {
        $domain = new \Brunty\Cigar\Domain('url', 418, 'teapot');
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
        $domain = new \Brunty\Cigar\Domain('url', 418, 'teapot');
        $results = [
            new \Brunty\Cigar\Result($domain, 418, 'teapot'),
            new \Brunty\Cigar\Result($domain, 419),
        ];

        $fn = function() use ($results) {
            (new Outputter(true))->outputResults($results);
        };

        expect($fn)->toEcho('');
    });

    it('outputs the stats of the execution with multiple results', function() {
        $domain = new \Brunty\Cigar\Domain('url', 418, 'teapot');
        $results = [
            new \Brunty\Cigar\Result($domain, 418, 'teapot'),
            new \Brunty\Cigar\Result($domain, 419),
        ];

        allow('microtime')->toBeCalled()->andReturn(2.5);

        $fn = function() use ($results) {
            (new Outputter)->outputStats(true, $results, 1.0);
        };

        $output = PHP_EOL . 'Checked 2 URLs in 1.5s' . PHP_EOL . PHP_EOL;

        expect($fn)->toEcho($output);
    });

    it('outputs the stats of the execution with a single result', function() {
        $domain = new \Brunty\Cigar\Domain('url', 418, 'teapot');
        $results = [
            new \Brunty\Cigar\Result($domain, 418, 'teapot')
        ];

        allow('microtime')->toBeCalled()->andReturn(2.5);

        $fn = function() use ($results) {
            (new Outputter)->outputStats(true, $results, 1.0);
        };

        $output = PHP_EOL . 'Checked 1 URL in 1.5s' . PHP_EOL . PHP_EOL;

        expect($fn)->toEcho($output);
    });
});
