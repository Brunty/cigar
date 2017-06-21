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
            (new Outputter)->outputResults($results, true);
        };

        expect($fn)->toEcho('');
    });
});
