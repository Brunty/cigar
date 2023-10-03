<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class Url
{
    public function __construct(
        public readonly string $url,
        public readonly int $status,
        public readonly string $content = '',
        public readonly string $contentType = '',
        public readonly ?int $connectTimeout = null,
        public readonly ?int $timeout = null
    ) {
    }
}
