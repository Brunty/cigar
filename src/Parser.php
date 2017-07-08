<?php

namespace Brunty\Cigar;

class Parser
{

    public function parse(string $filename): array
    {
        $urls = json_decode(file_get_contents($filename), true);

        if($urls === null) {
            throw new \ParseError('Could not parse ' . $filename);
        }

        return array_map(function($value) {
            return new Url($value['url'], $value['status'], $value['content'] ?? null);
        }, $urls);
    }
}
