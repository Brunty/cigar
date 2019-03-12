<?php

namespace Brunty\Cigar;

class Parser
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var null|int
     */
    private $connectTimeout;

    /**
     * @var null|int
     */
    private $timeout;

    public function __construct(string $baseUrl = null, int $connectTimeout = null, int $timeout = null)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->connectTimeout = $connectTimeout;
        $this->timeout = $timeout;
    }

    /**
     * @param string $filename
     *
     * @return Url[]
     * @throws \ParseError
     */
    public function parse(string $filename): array
    {
        $urls = json_decode(file_get_contents($filename), true);

        if($urls === null) {
            throw new \ParseError('Could not parse ' . $filename);
        }

        return array_map(function($value) {
            $url = $this->getUrl($value['url']);

            return new Url(
                $url,
                $value['status'],
                $value['content'] ?? null,
                $value['content-type'] ?? null,
                $value['connect-timeout'] ?? $this->connectTimeout,
                $value['timeout'] ?? $this->timeout
            );
        }, $urls);
    }

    /**
     * @param string $url
     *
     * @return string
     * @throws \ParseError
     */
    private function getUrl(string $url): string
    {
        $urlParts = parse_url($url);

        if ($urlParts === false) {
            throw new \ParseError("Could not parse URL: $url");
        }

        if ($this->baseUrl !== null && ! isset($urlParts['host'])) {
            $url = $this->baseUrl . '/' . ltrim($url, '/');
        }

        return $url;
    }
}
