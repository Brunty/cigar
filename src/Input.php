<?php

namespace Brunty\Cigar;

use InvalidArgumentException;

class Input
{
    private string $optionShortCodes = '';
    private array $optionLongCodes = [];
    /** @var array<string, InputOption> */
    private array $options = [];
    private array $submittedOptions = [];

    /**
     * @param array<string, InputOption> $options
     */
    public function configureOptions(array $options): void
    {
        ksort($options);
        $this->options = $options;
        foreach ($options as $inputOption) {
            $this->optionShortCodes .= $inputOption->fullShortCode();
            $this->optionLongCodes[] = $inputOption->fullLongCode();
        }
    }

    /**
     * @return array<string, InputOption>
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * If the option was configured with {@see InputOption::VALUE_REQUIRED} then it will be the string value of what was
     * submitted via the option on the command line, if it's set to {@see InputOption::VALUE_NONE} then it'll be just
     * true / false based on if the option was set on the command line or not
     *
     * @param string $optionName
     * @return array|string|bool
     */
    public function getOption(string $optionName): array|string|bool
    {
        if (array_key_exists($optionName, $this->options) === false) {
            throw new InvalidArgumentException("Could not find option with {$optionName}");
        }

        if ($this->submittedOptions === []) {
            $this->submittedOptions = getopt($this->optionShortCodes, $this->optionLongCodes);
        }

        $option = $this->options[$optionName];

        $longOptionWasSubmitted = array_key_exists($option->longCode, $this->submittedOptions);
        $shortOptionWasSubmitted = array_key_exists($option->shortCode, $this->submittedOptions);

        if ($option->valueIsRequired()) {
            if ($longOptionWasSubmitted) {
                return $this->submittedOptions[$option->longCode];
            }

            if ($shortOptionWasSubmitted) {
                return $this->submittedOptions[$option->shortCode];
            }

            return false;
        }

        return $longOptionWasSubmitted || $shortOptionWasSubmitted;
    }
}
