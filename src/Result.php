<?php

namespace Brunty\Cigar;

class Result
{
    /**
     * @var Domain
     */
    private $domain;
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var string
     */
    private $contents;

    public function __construct(Domain $domain, int $statusCode, ?string $contents = null)
    {
        $this->domain = $domain;
        $this->statusCode = $statusCode;
        $this->contents = $contents;
    }

    public function passed(): bool
    {
        return $this->statusMatches() && $this->responseHasContent();
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getDomain(): Domain
    {
        return $this->domain;
    }

    /**
     * @return bool
     */
    private function statusMatches(): bool
    {
        return $this->statusCode === $this->domain->getStatus();
    }

    private function responseHasContent(): bool
    {
        $expectedContent = $this->domain->getContent();

        if ($expectedContent === null) {
            return true; // nothing to check
        }

        return (bool) strstr($this->contents, $this->domain->getContent());

    }
}
