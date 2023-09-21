<?php

declare(strict_types=1);

namespace Brunty\Cigar;

class Result
{
    public function __construct(
        public readonly Url $url,
        public readonly int $statusCode,
        public readonly ?string $contents = null,
        public readonly ?string $contentType = null
    ) {
    }

    public function hasPassed(): bool
    {
        return $this->statusMatches() && $this->responseMatchesContentType() && $this->responseHasContent();
    }

    private function statusMatches(): bool
    {
        return $this->statusCode === $this->url->status;
    }

    private function responseMatchesContentType(): bool
    {
        $expectedContentType = $this->url->contentType;

        if ($expectedContentType === null || $this->contentType === null) {
            return true; // nothing to check
        }

        return (bool) strstr(strtolower($this->contentType), strtolower($expectedContentType));
    }

    private function responseHasContent(): bool
    {
        $expectedContent = $this->url->content;

        if ($expectedContent === null) {
            return true; // nothing to check
        }

        return (bool) strstr((string) $this->contents, $expectedContent);
    }
}
