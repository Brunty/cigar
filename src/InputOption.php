<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class InputOption
{
    public const VALUE_NONE = 1;
    public const VALUE_REQUIRED = 2;

    private function __construct(
        readonly public string $longCode,
        readonly public string $shortCode,
        readonly public int $valueMode,
        readonly public string $description = '',
        readonly public mixed $default = null
    ) {
    }

    public static function create(
        string $longCode,
        string $shortCode = '',
        int $valueMode = self::VALUE_NONE,
        string $description = '',
        mixed $default = null
    ): self {
        return new self($longCode, $shortCode, $valueMode, $description, $default);
    }

    public function fullShortCode(): string
    {
        return $this->shortCode . ($this->valueMode === self::VALUE_REQUIRED && $this->shortCode !== '' ? ':' : '');
    }

    public function fullLongCode(): string
    {
        return $this->longCode . ($this->valueMode === self::VALUE_REQUIRED && $this->longCode !== '' ? ':' : '');
    }

    public function valueIsRequired(): bool
    {
        return $this->valueMode === self::VALUE_REQUIRED;
    }
}
