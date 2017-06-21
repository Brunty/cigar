<?php

namespace Brunty\Cigar;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AsyncChecker
{
    /**
     * @param Domain[] $domains
     *
     * @return Result[]
     */
    public function check(array $domains)
    {
        $client = new Client();

        $promises = [];
        foreach ($domains as $domain) {
            $url = $domain->getUrl();
            $promises[$url] = $client->getAsync($url);
        }

        $results = [];
        foreach($promises as $key => $promise) {
            try {
                $results[$key] = $promise->wait();
            } catch(RequestException $exception) {
                $results[$key] = $exception->getResponse();
            }
        }

        $output = [];
        foreach ($domains as $domain) {
            $key = $domain->getUrl();
            if ($results[$key]) {
                $content = $domain->getContent() !== null ? $results[$key]->getBody()->getContents() : null;
                $output[] = new Result($domain, (int) $results[$key]->getStatusCode(), $content);
            } else {
                $output[] = new Result($domain, (int) 0);
            }
        }

        return $output;
    }
}
