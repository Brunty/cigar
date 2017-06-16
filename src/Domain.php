<?php
declare(strict_types=1);

namespace Brunty\Cigar;

class Domain
{
    private $url;

    private $status;

    public function __construct(string $url, int $status)
    {
        $this->url = $url;
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
