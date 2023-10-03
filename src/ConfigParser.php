<?php

declare(strict_types=1);

namespace Brunty\Cigar;

use JsonException;
use ParseError;
use const JSON_THROW_ON_ERROR;

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
        try {
            $urls = (array) json_decode(file_get_contents($filename), true, 3, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new ParseError('Could not parse ' . $filename);
        }

        return array_map(function (array $value) {
            $url = $this->getUrl((string) $value['url']);

            return new Url(
                $url,
                (int) $value['status'],
                array_key_exists('content', $value) ? (string) $value['content'] : '',
                array_key_exists('content-type', $value) ? (string) $value['content-type'] : '',
                array_key_exists('connect-timeout', $value) ? (int) $value['connect-timeout'] : $this->connectTimeout,
                array_key_exists('timeout', $value) ? (int) $value['timeout'] : $this->timeout,
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
