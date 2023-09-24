<?php

declare(strict_types=1);

namespace Brunty\Cigar;

use ParseError;

class ConfigParser
{
    private string $baseUrl;

    public function __construct(
        ?string $baseUrl = null,
        private readonly ?int $connectTimeout = null,
        private readonly ?int $timeout = null
    ) {
        $this->baseUrl = rtrim((string) $baseUrl, '/');
    }

    /**
     * @return Url[]
     *
     * @throws ParseError
     */
    public function parse(string $filename): array
    {
        $urls = json_decode(file_get_contents($filename), true);

        if ($urls === null) {
            throw new ParseError('Could not parse ' . $filename);
        }

        return array_map(function (array $value) {
            $url = $this->getUrl($value['url']);

            return new Url(
                $url,
                $value['status'],
                $value['content'] ?? '',
                $value['content-type'] ?? '',
                $value['connect-timeout'] ?? $this->connectTimeout,
                $value['timeout'] ?? $this->timeout
            );
        }, $urls);
    }

    /**
     * @throws ParseError
     */
    private function getUrl(string $url): string
    {
        $urlParts = parse_url($url);

        if ($urlParts === false) {
            throw new ParseError("Could not parse URL: $url");
        }

        if ($this->baseUrl !== '' && !isset($urlParts['host'])) {
            $url = $this->baseUrl . '/' . ltrim($url, '/');
        }

        return $url;
    }
}
