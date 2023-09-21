<?php

namespace Brunty\Cigar;

use const STR_PAD_RIGHT;

class Output
{
    private Writer $writer;

    private const VALUE_PLACEHOLDER = 'VALUE';

    public function __construct(Writer $writer = null)
    {
        $this->writer = $writer instanceof Writer ? $writer : new EchoWriter();
    }

    public function writeErrorLine(string $message): void
    {
        $this->writer->writeErrorLine($message);
    }

    public function outputResults(array $passedResults, array $results, float $startTime): void
    {
        $numberOfResults = count($results);
        $numberOfPassedResults = count($passedResults);
        $endTime = microtime(true);
        $timeDiff = round($endTime - $startTime, 3);
        $passed = $numberOfPassedResults === $numberOfResults;

        $this->writer->writeResults($numberOfPassedResults, $numberOfResults, $passed, $timeDiff, ...$results);
    }

    public function helpOutputForInputOptions(Input $input): string
    {
        $optionStartSequence = "\033[32m";
        $optionEndSequence = "\033[0m";

        $output = '';

        if ($input->options() !== []) {
            $output = "\033[33mOptions:\033[0m" . PHP_EOL;
        }

        $longestShortCodeLength = 0;
        $longestLongCodeLength = 0;
        $valuePlaceholderLength = mb_strlen(self::VALUE_PLACEHOLDER);

        foreach ($input->options() as $option) {
            $shortCodeLength = 3; // at it's shortest it is "-c "
            $longCodeLength = mb_strlen($option->longCode) + 3; // the +3 is for the double dash before and the space or equals after

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

        foreach ($input->options() as $option) {
            $shortCode = $this->getShortCodeOutput($option);
            $longCode = $this->getLongCodeOutput($option);

            $shortCode = str_pad($shortCode, $longestShortCodeLength, ' ');
            $longCode = str_pad($longCode, $longestLongCodeLength, ' ');

            $output .= "  {$optionStartSequence}{$shortCode}  {$longCode}{$optionEndSequence}  {$option->description}" .
                PHP_EOL;
        }

        return $output;
        /*
\033[33mOptions:\033[0m
      \033[32m-c file.json, --config=file.json\033[0m         Use the specified config file instead of the default cigar.json file
      \033[32m-u URL,       --url=URL\033[0m                  Base URL for checks, e.g. https://example.org/
      \033[32m-i,           --insecure\033[0m                 Allow invalid SSL certificates
      \033[32m-a,           --auth\033[0m                     Authorization header "\074type\076 \074credentials\076"
      \033[32m-h,           --header\033[0m                   Custom header "\074name\076: \074value\076"
      \033[32m-s            --connect-timeout=TIMEOUT\033[0m  Connect Timeout
      \033[32m-t,           --timeout=TIMEOUT\033[0m          Timeout
      \033[32m-j,           --json\033[0m                     Output JSON
      \033[32m              --quiet\033[0m                    Do not output any message
      \033[32m              --version\033[0m                  Print the version of Cigar
         */
    }

    private function getShortCodeOutput(InputOption $option): string
    {
        $shortCode = '';

        if ($option->shortCode !== '') {
            $shortCode = "-{$option->shortCode}";
        }

        if ($option->valueIsRequired()) {
            $shortCode = "-{$option->shortCode} VALUE";
        }

        return $shortCode;
    }

    private function getLongCodeOutput(InputOption $option): string
    {
        $longCode = "--{$option->longCode}";

        if ($option->valueIsRequired()) {
            $longCode = "--{$option->longCode}=VALUE";
        }

        return $longCode;
    }
}
