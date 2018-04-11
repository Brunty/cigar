<?php

use Brunty\Cigar\JsonWriter;
use Brunty\Cigar\Outputter;

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

    it('outputs results', function () {
        $results = $this->results;

        $fn = function () use ($results) {
            $writer = new JsonWriter();
            foreach ($results as $result) {
                $writer->writeLine($result);
            }
        };

        $output = <<< OUTPUT
{"type":"result","passed":true,"url":"url","status_code_expected":418,"status_code_actual":418,"content_type_expected":"teapot","content_type_actual":"teapot","content_expected":"teapot"}
{"type":"result","passed":false,"url":"url","status_code_expected":418,"status_code_actual":419,"content_type_expected":"teapot","content_type_actual":null,"content_expected":"teapot"}

OUTPUT;

        expect($fn)->toEcho($output);
    });

    it('outputs the stats of the execution if all results have passed', function () {
        $fn = function () {
            (new JsonWriter)->writeStats(2, 2, true, 1.5);
        };

        expect($fn)->toEcho('{"type":"stats","passed":true,"results_count":2,"passed_results_count":2,"time_taken":1.5}' . PHP_EOL);
    });

    it('outputs the stats of the execution if some URLs have failed', function () {
        $fn = function () {
            (new JsonWriter)->writeStats(1, 2, false, 1.5);
        };

        expect($fn)->toEcho('{"type":"stats","passed":false,"results_count":2,"passed_results_count":1,"time_taken":1.5}' . PHP_EOL);
    });
});
