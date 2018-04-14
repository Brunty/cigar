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

    public function writeResults(int $numberOfPassedResults, int $numberOfResults, bool $passed, float $timeDiff, Result ...$results)
    {
        echo json_encode([
            'type' => 'results',
            'time_taken' => $timeDiff,
            'passed' => $passed,
            'results_count' => $numberOfResults,
            'results_passed_count' => $numberOfPassedResults,
            'results' => array_map([$this, 'line'], $results),
        ]), PHP_EOL;
    }

    private function line(Result $result): array
    {
        return [
            'passed' => $result->hasPassed(),
            'url' => $result->getUrl()->getUrl(),
            'status_code_expected' => $result->getUrl()->getStatus(),
            'status_code_actual' => $result->getStatusCode(),
            'content_type_expected' => $result->getUrl()->getContentType(),
            'content_type_actual' => $result->getContentType(),
            'content_expected' => $result->getUrl()->getContent(),
        ];
    }
}
