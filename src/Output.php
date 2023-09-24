<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class Output
{
    private const VALUE_PLACEHOLDER = 'VALUE';

    public function __construct(private readonly Writer $writer, private readonly Timer $timer)
    {
    }

    public function writeErrorLine(string $message): void
    {
        $this->writer->writeErrorLine($message);
    }

    public function outputResults(Results $results, float $startedAt): void
    {
        $timeDiff = round($this->timer->stop() - $startedAt, 3);

        $this->writer->writeResults($results, $timeDiff);
    }

    public function helpOutputForInputOptions(InputOptions $inputOptions): string
    {
        $optionStartSequence = "\033[32m";
        $optionEndSequence = "\033[0m";

        $output = '';

        if ($inputOptions->options !== []) {
            $output = "\033[33mOptions:\033[0m" . PHP_EOL;
        }

        $longestShortCodeLength = 0;
        $longestLongCodeLength = 0;
        $valuePlaceholderLength = mb_strlen(self::VALUE_PLACEHOLDER);

        foreach ($inputOptions->options as $option) {
            $shortCodeLength = 3; // at it's shortest it is "-c "
            $longCodeLength = mb_strlen($option->longCode) + 3; // +3 is for double dash before & the space or = after

            if ($option->valueIsRequired()) {
                $shortCodeLength += $valuePlaceholderLength;
                $longCodeLength += $valuePlaceholderLength;
            }

            if ($shortCodeLength > $longestShortCodeLength) {
                $longestShortCodeLength = $shortCodeLength;
            }

            if ($longCodeLength > $longestLongCodeLength) {
                $longestLongCodeLength = $longCodeLength;
            }
        }

        foreach ($inputOptions->options as $option) {
            $shortCode = $this->getShortCodeOutput($option);
            $longCode = $this->getLongCodeOutput($option);

            $shortCode = str_pad($shortCode, $longestShortCodeLength);
            $longCode = str_pad($longCode, $longestLongCodeLength);


            $output .= sprintf(
                '  %s%s  %s%s  %s' . PHP_EOL,
                $optionStartSequence,
                $shortCode,
                $longCode,
                $optionEndSequence,
                $option->description
            );
        }

        return $output;
    }

    private function getShortCodeOutput(InputOption $option): string
    {
        $shortCode = '';

        if ($option->shortCode !== '') {
            $shortCode = "-$option->shortCode";
        }

        if ($option->valueIsRequired()) {
            $shortCode = "-$option->shortCode " . self::VALUE_PLACEHOLDER;
        }

        return $shortCode;
    }

    private function getLongCodeOutput(InputOption $option): string
    {
        $longCode = "--$option->longCode";

        if ($option->valueIsRequired()) {
            $longCode = "--$option->longCode=" . self::VALUE_PLACEHOLDER;
        }

        return $longCode;
    }
}
