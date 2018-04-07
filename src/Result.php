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

    /**
     * @var null|string
     */
    private $contentType;

    public function __construct(Url $url, int $statusCode, string $contents = null, string $contentType = null)
    {
        $this->url = $url;
        $this->statusCode = $statusCode;
        $this->contents = $contents;
        $this->contentType = $contentType;
    }

    public function hasPassed(): bool
    {
        return $this->statusMatches() && $this->responseMatchesContentType() && $this->responseHasContent();
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    public function getContents()
    {
        return $this->contents;
    }

    private function statusMatches(): bool
    {
        return $this->statusCode === $this->url->getStatus();
    }

    private function responseMatchesContentType(): bool
    {
        $expectedContentType = $this->url->getContentType();

        if ($expectedContentType === null || $this->contentType === null) {
            return true; // nothing to check
        }

        return (bool) strstr(strtolower($this->contentType), strtolower($expectedContentType));
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
