<?php

namespace Brunty\Cigar;

class Parser
{

    public function parse(string $filename)
    {
        $contents = file_get_contents($filename);
        $lines = explode("\n", $contents);
        $domains = [];

        foreach ($lines as $line) {
            if(trim($line) !== '') {
                [$url, $status] = explode(' ', $line);
                $domains[] = new Domain($url, $status);
            }
        }

        return $domains;
    }
}
