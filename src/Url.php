<?php
declare(strict_types=1);

namespace Brunty\Cigar;

class Url
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $status;

    /**
     * @var null|string
     */
    private $content;

    public function __construct(string $url, int $status, ?string $content = null)
    {
        $this->url = $url;
        $this->status = $status;
        $this->content = $content;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
