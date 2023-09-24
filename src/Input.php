<?php

declare(strict_types=1);

namespace Brunty\Cigar;

use InvalidArgumentException;

class Input
{
    public function __construct(private readonly InputOptions $inputOptions, private readonly array $submittedOptions)
    {
    }

    /**
     * If the option was configured with {@see InputOption::VALUE_REQUIRED} then it will be the string value of what was
     * submitted via the option on the command line if supplied, if not it'll be what's configured as the default for
     * that option, if it's set to {@see InputOption::VALUE_NONE} then it'll be just true / false based on if the option
     * was set on the command line or not
     */
    public function getOption(string $optionName): mixed
    {
        if (array_key_exists($optionName, $this->inputOptions->options) === false) {
            throw new InvalidArgumentException("Could not find option with $optionName");
        }

        $option = $this->inputOptions->options[$optionName];

        $longOptionWasSubmitted = array_key_exists($option->longCode, $this->submittedOptions);
        $shortOptionWasSubmitted = array_key_exists($option->shortCode, $this->submittedOptions);

        if ($option->valueIsRequired()) {
            if ($longOptionWasSubmitted) {
                return $this->submittedOptions[$option->longCode] ?: $option->default;
            }

            if ($shortOptionWasSubmitted) {
                return $this->submittedOptions[$option->shortCode] ?: $option->default;
            }

            return $option->default;
        }

        return $longOptionWasSubmitted || $shortOptionWasSubmitted;
    }
}
