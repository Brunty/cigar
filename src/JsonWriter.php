<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class JsonWriter implements Writer
{
    public function writeErrorLine(string $message): void
    {
        echo json_encode([
            'type' => 'error',
            'message' => $message,
        ]), PHP_EOL;
    }

    public function writeResults(
        int $numberOfPassedResults,
        int $numberOfResults,
        bool $passed,
        float $timeDiff,
        Result ...$results
    ): void {
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
            'url' => $result->url->url,
            'status_code_expected' => $result->url->status,
            'status_code_actual' => $result->statusCode,
            'content_type_expected' => $result->url->contentType,
            'content_type_actual' => $result->contentType,
            'content_expected' => $result->url->content,
        ];
    }
}
