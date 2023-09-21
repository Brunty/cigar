<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class Url
{
    public function __construct(
        public readonly string $url,
        public readonly int $status,
        public readonly ?string $content = null,
        public readonly ?string $contentType = null,
        public readonly ?int $connectTimeout = null,
        public readonly ?int $timeout = null
    ) {
    }
}
