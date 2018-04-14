<?php

namespace Brunty\Cigar;

class Outputter
{
    /**
     * @var bool
     */
    private $isQuiet;

    /**
     * @var WriterInterface
     */
    private $writer;

    public function __construct(bool $isQuiet = false, WriterInterface $writer = null)
    {
        $this->isQuiet = $isQuiet;
        $this->writer = $writer instanceof WriterInterface ? $writer : new EchoWriter();
    }

    public function writeErrorLine(string $message)
    {
        if ($this->isQuiet) {
            return;
        }

        $this->writer->writeErrorLine($message);
    }

    public function outputResults(array $passedResults, array $results, float $startTime)
    {
        if ($this->isQuiet) {
            return;
        }

        $numberOfResults = count($results);
        $numberOfPassedResults = count($passedResults);
        $end = microtime(true);
        $timeDiff = round($end - $startTime, 3);
        $passed = $numberOfPassedResults === $numberOfResults;

        $this->writer->writeResults($numberOfPassedResults, $numberOfResults, $passed, $timeDiff, ...$results);
    }
}
