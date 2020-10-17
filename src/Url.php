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

    /**
     * @var null|string
     */
    private $contentType;

    /**
     * @var null|int
     */
    private $connectTimeout;

    /**
     * @var null|int
     */
    private $timeout;

    public function __construct(
        string $url,
        int $status,
        string $content = null,
        string $contentType = null,
        int $connectTimeout = null,
        int $timeout = null
    ) {
        $this->url = $url;
        $this->status = $status;
        $this->content = $content;
        $this->contentType = $contentType;
        $this->connectTimeout = $connectTimeout;
        $this->timeout = $timeout;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return null|string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return null|string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return null|int
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * @return null|int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}
