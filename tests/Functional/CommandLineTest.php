<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class CommandLineTest extends TestCase
{
    public function tearDown(): void
    {
        // clear out cigar config after each test
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && rm cigar.json');
        $process->run();
    }

    #[Test]
    public function it_displays_help_output(): void
    {
        $process = Process::fromShellCommandline('./bin/cigar --help');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
        $this->assertStringContainsString('--connect-timeout=VALUE', $process->getOutput());
    }

    #[Test]
    public function it_displays_version_output(): void
    {
        $process = Process::fromShellCommandline('./bin/cigar --version');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
        $this->assertStringContainsString('Version', $process->getOutput());
    }

    #[Test]
    public function it_passes_if_given_good_urls_to_check(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp cigar.pass.json cigar.json && ../../bin/cigar');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
    }

    #[Test]
    public function it_is_quiet_if_the_option_is_passed(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp cigar.pass.json cigar.json && ../../bin/cigar --quiet');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
        $this->assertSame('', $process->getOutput());
    }

    #[Test]
    public function it_fails_if_given_bad_urls_to_check(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp cigar.fail.json cigar.json && ../../bin/cigar');
        $process->run();

        $this->assertSame(1, $process->getExitCode());
    }
}
