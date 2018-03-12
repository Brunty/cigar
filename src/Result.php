<?php

namespace Brunty\Cigar;

class Result
{
    /**
     * @var Url
     */
    private $url;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $contents;

    public function __construct(Url $url, int $statusCode, string $contents = null)
    {
        $this->url = $url;
        $this->statusCode = $statusCode;
        $this->contents = $contents;
    }

    public function hasPassed(): bool
    {
        return $this->statusMatches() && $this->responseHasContent();
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    private function statusMatches(): bool
    {
        return $this->statusCode === $this->url->getStatus();
    }

    private function responseHasContent(): bool
    {
        $expectedContent = $this->url->getContent();

        if ($expectedContent === null) {
            return true; // nothing to check
        }

        return (bool) strstr($this->contents, $expectedContent);
    }
}
