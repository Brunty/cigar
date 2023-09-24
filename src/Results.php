<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class Results
{
    /** @var Result[] */
    public readonly array $results;

    private ?int $passedResults = null;

    public function __construct(Result ...$results)
    {
        $this->results = $results;
    }

    public function numberOfPassedResults(): int
    {
        if ($this->passedResults !== null) {
            return $this->passedResults;
        }

        $this->passedResults = 0;

        foreach ($this->results as $result) {
            if ($result->hasPassed()) {
                $this->passedResults++;
            }
        }

        return $this->passedResults;
    }

    public function numberOfTotalResults(): int
    {
        return count($this->results);
    }

    public function hasPassed(): bool
    {
        return $this->numberOfTotalResults() === $this->numberOfPassedResults();
    }
}
