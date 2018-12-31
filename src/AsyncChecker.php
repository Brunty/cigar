<?php

namespace Brunty\Cigar;

class AsyncChecker
{

    /**
     * @var bool
     */
    private $checkSsl;

    /**
     * @var string
     */
    private $authorizationHeader;

    /**
     * @var array
     */
    private $headers;

    public function __construct(bool $checkSsl = true, string $authorizationHeader = null, array $headers = [])
    {
        $this->checkSsl = $checkSsl;
        $this->authorizationHeader = $authorizationHeader;
        $this->headers = $headers;
    }

    /**
     * @param Url[] $urlsToCheck
     *
     * @return Result[]
     */
    public function check(array $urlsToCheck): array
    {
        $mh = curl_multi_init();
        $channels = [];

        foreach ($urlsToCheck as $urlToCheck) {
            $channel = curl_init();
            $url = $urlToCheck->getUrl();
            curl_setopt($channel, CURLOPT_URL, $url);
            curl_setopt($channel, CURLOPT_HEADER, 0);
            curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);

            if ( ! $this->checkSsl) {
                curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($channel, CURLOPT_SSL_VERIFYHOST, 0);
            }

            if ($this->authorizationHeader) {
                $this->headers[] = "Authorization: {$this->authorizationHeader}";
            }

            if (!empty($this->headers)) {
                curl_setopt($channel, CURLOPT_HTTPHEADER, $this->headers);
            }

            if ($urlToCheck->getConnectTimeout() !== null && $urlToCheck->getConnectTimeout() > 0) {
                curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, $urlToCheck->getConnectTimeout());
            }
            if ($urlToCheck->getTimeout() !== null && $urlToCheck->getTimeout() > 0) {
                curl_setopt($channel, CURLOPT_TIMEOUT, $urlToCheck->getTimeout());
            }

            curl_multi_add_handle($mh, $channel);

            $channels[$url] = $channel;
        }

        $active = null;

        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        $return = [];
        foreach ($urlsToCheck as $urlToCheck) {
            $key = $urlToCheck->getUrl();
            $channel = $channels[$key];
            $code = (int) curl_getinfo($channel, CURLINFO_HTTP_CODE);
            $content = curl_multi_getcontent($channel);
            $contentType = curl_getinfo($channel, CURLINFO_CONTENT_TYPE) ?? null;

            $return[] = new Result($urlToCheck, $code, $content, $contentType);
            curl_multi_remove_handle($mh, $channel);
        }

        curl_multi_close($mh);

        return $return;
    }
}
