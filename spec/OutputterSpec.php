<?php

declare(strict_types=1);

use Brunty\Cigar\Output;
use Brunty\Cigar\Result;
use Brunty\Cigar\Url;

describe('Output', function (): void {
    beforeEach(function (): void {
        $this->domain = new Url('url', 418, 'teapot', 'teapot');
        $this->results = [
            new Result($this->domain, 418, 'teapot', 'teapot'),
            new Result($this->domain, 419),
        ];
        $this->passedResults = array_filter($this->results, function (Result $result) {
            return $result->hasPassed();
        });
    });

    it('outputs an error line', function (): void {
        $fn = function (): void {
            (new Output())->writeErrorLine('Error message');
        };

        expect($fn)->toEcho("\033[31mError message\033[0m\n");
    });

    it('outputs results if some results have passed', function (): void {
        $results = $this->results;
        $passedResults = $this->passedResults;

        allow('microtime')->toBeCalled()->andReturn(3);

        $fn = function () use ($passedResults, $results): void {
            (new Output())->outputResults($passedResults, $results, 1.5);
        };

        $output = <<< OUTPUT
\e[32m✓ url [418:418] [teapot:teapot] teapot\e[0m
\e[31m✘ url [418:419] [teapot:] teapot\e[0m

[\e[31m1/2\e[0m] passed in 1.5s


OUTPUT;

        expect($fn)->toEcho($output);
    });
});
