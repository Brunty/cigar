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

    public function __construct(Domain $domain, int $statusCode)
    {
        $this->domain = $domain;
        $this->statusCode = $statusCode;
    }

    public function passed(): bool
    {
        return $this->statusCode === $this->domain->getStatus();
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getDomain(): Domain
    {
        return $this->domain;
    }
}
