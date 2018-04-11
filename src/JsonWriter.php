<?php

namespace Brunty\Cigar;

class JsonWriter implements WriterInterface
{
    public function writeErrorLine(string $message)
    {
        echo json_encode([
            'type' => 'error',
            'message' => $message,
        ]), PHP_EOL;
    }

    public function writeLine(Result $result)
    {
        echo json_encode([
            'type' => 'result',
            'passed' => $result->hasPassed(),
            'url' => $result->getUrl()->getUrl(),
            'status_code_expected' => $result->getUrl()->getStatus(),
            'status_code_actual' => $result->getStatusCode(),
            'content_type_expected' => $result->getUrl()->getContentType(),
            'content_type_actual' => $result->getContentType(),
            'content_expected' => $result->getUrl()->getContent(),
        ]), PHP_EOL;
    }

    public function writeStats(int $numberOfPassedResults, int $numberOfResults, bool $passed, float $timeDiff)
    {
        echo json_encode([
            'type' => 'stats',
            'passed' => $passed,
            'results_count' => $numberOfResults,
            'passed_results_count' => $numberOfPassedResults,
            'time_taken' => $timeDiff,
        ]), PHP_EOL;
    }
}
