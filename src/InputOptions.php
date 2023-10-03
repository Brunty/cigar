<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class InputOptions
{
    public readonly string $shortCodes;

    /** @var string[] */
    public readonly array $longCodes;

    /** @param array<string, InputOption> $options */
    public function __construct(public readonly array $options)
    {
        $shortCodes = '';
        $longCodes = [];

        ksort($options);

        foreach ($options as $inputOption) {
            $shortCodes .= $inputOption->fullShortCode();
            $longCodes[] = $inputOption->fullLongCode();
        }

        $this->shortCodes = $shortCodes;
        $this->longCodes = $longCodes;
    }
}
