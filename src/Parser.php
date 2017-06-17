<?php

namespace Brunty\Cigar;

class Parser
{

    public function parse(string $filename)
    {
        $contents = file_get_contents($filename);
        $lines = preg_split('/[\r\n]+/', $contents);
        $domains = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if($line !== '') {
                [$url, $status] = preg_split('/[\s]+/', $line);
                $domains[] = new Domain($url, $status);
            }
        }

        return $domains;
    }
}
