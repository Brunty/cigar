<?php

namespace Brunty\Cigar;

class Outputter
{
    public function output(array $results, bool $quiet = false)
    {
        $suitePassed = true;
        ob_start();
        foreach ($results as $result) {
            $passed = $result->passed();
            $colour = "\033[32m"; // green
            $status = "✓";

            if ( ! $passed) {
                $suitePassed = false;
                $colour = "\033[31m"; // red
                $status = "✘";
            }

            if( ! $quiet) {
                echo "{$colour}{$status} {$result->getDomain()->getUrl()} [{$result->getDomain()->getStatus()}:{$result->getStatusCode()}] \033[0m" . PHP_EOL;
            }
            ob_flush();
        }

        ob_end_flush();

        return $suitePassed;
    }
}
