<?php

declare(strict_types=1);

use Brunty\Cigar\JsonWriter;
use Brunty\Cigar\Result;
use Brunty\Cigar\Url;

describe('JsonWriter', function (): void {
    beforeEach(function (): void {
        $this->domain = new Url('url', 418, 'teapot', 'teapot');
        $this->results = [
            new Result($this->domain, 418, 'teapot', 'teapot'),
            new Result($this->domain, 419),
        ];
    });

    it('outputs an error line', function (): void {
        $fn = function (): void {
            (new JsonWriter())->writeErrorLine('Error message');
        };

        expect($fn)->toEcho('{"type":"error","message":"Error message"}' . PHP_EOL);
    });

    it('outputs results if all results have passed', function (): void {
        $results = $this->results;

        $fn = function () use ($results): void {
            $writer = new JsonWriter();
            $writer->writeResults(2, 2, true, 1.5, ...$results);
        };

        $output = file_get_contents(__DIR__ . '/output/output-results-all-passed.json');

        expect($fn)->toEcho($output);
    });

    it('outputs results if some URLs have failed', function (): void {
        $results = $this->results;

        $fn = function () use ($results): void {
            $writer = new JsonWriter();
            $writer->writeResults(1, 2, false, 1.5, ...$results);
        };

        $output = file_get_contents(__DIR__ . '/output/output-results-some-failed.json');

        expect($fn)->toEcho($output);
    });
});
