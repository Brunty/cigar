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

    public function outputResults(array $results)
    {
        if ($this->isQuiet) {
            return;
        }

        ob_start();

        foreach ($results as $result) {
            $this->writer->writeLine($result);
            ob_flush();
        }

        ob_end_flush();
    }

    public function outputStats(array $passedResults, array $results, float $startTime)
    {
        if ($this->isQuiet) {
            return;
        }

        $numberOfResults = count($results);
        $numberOfPassedResults = count($passedResults);
        $end = microtime(true);
        $timeDiff = round($end - $startTime, 3);
        $passed = $numberOfPassedResults === $numberOfResults;

        $this->writer->writeStats($numberOfPassedResults, $numberOfResults, $passed, $timeDiff);
    }
}
