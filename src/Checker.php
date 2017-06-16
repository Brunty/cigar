<?php

namespace Brunty\Cigar;

class Checker
{
    /**
     * @param Domain[] $domains
     *
     * @return Result[]
     */
    public function check(array $domains)
    {
        $results = [];
        foreach ($domains as $domain) {
            $http = curl_init($domain->getUrl());
            curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($http);
            $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
            curl_close($http);
            $results[] = new Result($domain, (int) $http_status);
        }

        return $results;
    }
}
