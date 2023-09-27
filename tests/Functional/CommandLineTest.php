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
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && rm cigar.json alt.json');
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
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp pass.json cigar.json && ../../bin/cigar');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
    }

    #[Test]
    public function it_is_quiet_if_the_option_is_passed(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp pass.json cigar.json && ../../bin/cigar --quiet');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
        $this->assertSame('', $process->getOutput());
    }

    #[Test]
    public function it_fails_if_given_bad_urls_to_check(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp fail.json cigar.json && ../../bin/cigar');
        $process->run();

        $this->assertSame(1, $process->getExitCode());
    }

    #[Test]
    public function it_can_be_given_an_alternative_config_file_by_a_short_command_line_flag(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp pass.json alt.json && ../../bin/cigar -f alt.json');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
    }

    #[Test]
    public function it_can_be_given_an_alternative_config_file_by_a_long_command_line_flag(): void
    {

        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp pass.json alt.json && ../../bin/cigar --config alt.json');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
    }

    #[Test]
    public function it_fails_with_insecure_certs(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp insecure.json cigar.json && ../../bin/cigar');
        $process->run();

        $this->assertSame(1, $process->getExitCode());
    }

    #[Test]
    public function it_passes_with_insecure_certs_if_the_long_flag_is_given(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp insecure.json cigar.json && ../../bin/cigar --insecure');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
    }

    #[Test]
    public function it_passes_with_insecure_certs_if_the_short_flag_is_given(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp insecure.json cigar.json && ../../bin/cigar -i');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
    }

    #[Test]
    public function it_can_be_passed_a_base_url_as_a_short_command_argument(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp without-base.json cigar.json && ../../bin/cigar -u http://httpbin.org');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
    }

    #[Test]
    public function it_can_be_passed_a_base_url_as_a_long_command_argument(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp without-base.json cigar.json && ../../bin/cigar --url http://httpbin.org');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
    }

    #[Test]
    public function it_can_be_passed_headers_as_long_options(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp headers.json cigar.json && ../../bin/cigar --header="X-Cigar-Header: some-header-content-here" --header="X-Cigar-Header-2: more-header-content-here"');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
    }

    #[Test]
    public function it_can_be_passed_headers_as_short_options(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp headers.json cigar.json && ../../bin/cigar -h "X-Cigar-Header: some-header-content-here" -h "X-Cigar-Header-2: more-header-content-here"');
        $process->run();

        $this->assertSame(0, $process->getExitCode());
    }

    #[Test]
    public function it_can_bt_passed_timeout_arguments_and_overwrites_the_configured_value_in_the_config_file(): void
    {
        $process = Process::fromShellCommandline('cd tests/ConfigExamples && cp timeouts.json cigar.json && ../../bin/cigar -t 1');
        $process->run();

        $this->assertSame(1, $process->getExitCode());
    }
}
