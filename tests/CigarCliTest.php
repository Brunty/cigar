<?php

namespace Brunty\Cigar\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class CigarCliTest extends TestCase
{

    /**
     * @test
     */
    public function it_passes_if_given_good_urls()
    {
        $process = new Process('cd tests && cp stubs/.cigar.pass.json .cigar.json && ../bin/cigar');
        $process->run();

        self::assertEquals(0, $process->getExitCode());
    }

    /**
     * @test
     */
    public function it_is_quiet_if_given_the_option()
    {
        $process = new Process('cd tests && cp stubs/.cigar.pass.json .cigar.json && ../bin/cigar --quiet');
        $process->run();

        self::assertEquals('', $process->getOutput());
        self::assertEquals(0, $process->getExitCode());
    }

    /**
     * @test
     */
    public function it_fails_if_given_bad_urls()
    {
        $process = new Process('cd tests && cp .cigar.fail.json .cigar.json && ../bin/cigar');
        $process->run();

        self::assertEquals(1, $process->getExitCode());
    }
}
