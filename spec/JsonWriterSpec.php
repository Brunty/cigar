<?php

use Brunty\Cigar\JsonWriter;

describe('JsonWriter', function () {
    beforeEach(function() {
        $this->domain = new \Brunty\Cigar\Url('url', 418, 'teapot', 'teapot');
        $this->results = [
            new \Brunty\Cigar\Result($this->domain, 418, 'teapot', 'teapot'),
            new \Brunty\Cigar\Result($this->domain, 419),
        ];
    });

    it('outputs an error line', function () {
        $fn = function () {
            (new JsonWriter)->writeErrorLine('Error message');
        };

        expect($fn)->toEcho('{"type":"error","message":"Error message"}' . PHP_EOL);
    });

    it('outputs results if all results have passed', function () {
        $results = $this->results;

        $fn = function () use ($results) {
            $writer = new JsonWriter();
            $writer->writeResults(2, 2, true, 1.5, ...$results);
        };

        $output = <<< OUTPUT
{"type":"results","time_taken":1.5,"passed":true,"results_count":2,"results_passed_count":2,"results":[{"passed":true,"url":"url","status_code_expected":418,"status_code_actual":418,"content_type_expected":"teapot","content_type_actual":"teapot","content_expected":"teapot"},{"passed":false,"url":"url","status_code_expected":418,"status_code_actual":419,"content_type_expected":"teapot","content_type_actual":null,"content_expected":"teapot"}]}

OUTPUT;

        expect($fn)->toEcho($output);
    });

    it('outputs results if some URLs have failed', function () {
        $results = $this->results;

        $fn = function () use ($results) {
            $writer = new JsonWriter();
            $writer->writeResults(1, 2, false, 1.5, ...$results);
        };

        $output = <<< OUTPUT
{"type":"results","time_taken":1.5,"passed":false,"results_count":2,"results_passed_count":1,"results":[{"passed":true,"url":"url","status_code_expected":418,"status_code_actual":418,"content_type_expected":"teapot","content_type_actual":"teapot","content_expected":"teapot"},{"passed":false,"url":"url","status_code_expected":418,"status_code_actual":419,"content_type_expected":"teapot","content_type_actual":null,"content_expected":"teapot"}]}

OUTPUT;

        expect($fn)->toEcho($output);
    });
});
