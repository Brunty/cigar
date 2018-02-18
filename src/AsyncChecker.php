<?php

namespace Brunty\Cigar;

class AsyncChecker
{
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

            $return[] = new Result($urlToCheck, $code, $content);
            curl_multi_remove_handle($mh, $channel);
        }

        curl_multi_close($mh);

        return $return;
    }
}
