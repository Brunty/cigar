<?php
declare(strict_types=1);

namespace Brunty\Cigar;

class Domain
{
    private $url;
    private $status;
    private $content;

    public function __construct(string $url, int $status, ?string $content = null)
    {
        $this->url = $url;
        $this->status = $status;
        $this->content = $content;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
