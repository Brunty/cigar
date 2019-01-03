<?php

use Symfony\Component\Process\Process;

describe('Cigar CLI Tool', function () {

    // this is here to clean up config files from each test
    afterEach(function () {
        $process = new Process('cd spec && rm .*.json');
        $process->run();
    });

    it('passes if given good URLs to check', function () {
        $process = new Process('cd spec && cp stubs/.cigar.pass.json .cigar.json && ../bin/cigar');
        $process->run();

        expect($process->getExitCode())->toBe(0);
    });

    it('is quiet if given the option', function () {
        $process = new Process('cd spec && cp stubs/.cigar.pass.json .cigar.json && ../bin/cigar --quiet');
        $process->run();

        expect($process->getOutput())->toBe('');
        expect($process->getExitCode())->toBe(0);
    });

    it('fails if given bad URLs to check', function () {
        $process = new Process('cd spec && cp stubs/.cigar.fail.json .cigar.json && ../bin/cigar');
        $process->run();

        expect($process->getExitCode())->toBe(1);
    });

    it('can be given an alternative configuration file to load with a short command line flag', function () {
        $process = new Process('cd spec && cp stubs/.cigar.pass.json .config.json && ../bin/cigar -c .config.json');
        $process->run();

        expect($process->getExitCode())->toBe(0);
    });

    it('can be given an alternative configuration file to load with a long command line flag', function () {
        $process = new Process('cd spec && cp stubs/.cigar.pass.json .config.json && ../bin/cigar --config .config.json');
        $process->run();

        expect($process->getExitCode())->toBe(0);
    });

    it('fails with insecure certs', function () {
        $process = new Process('cd spec && cp stubs/.cigar.insecure.json .cigar.json && ../bin/cigar');
        $process->run();

        expect($process->getExitCode())->toBe(1);
    });
    
    it('passes with insecure certs if the insecure flag is specified', function () {
        $process = new Process('cd spec && cp stubs/.cigar.insecure.json .cigar.json && ../bin/cigar --insecure');
        $process->run();

        expect($process->getExitCode())->toBe(0);
    });

    it('can be passed a base URL as a short command argument', function () {
        $process = new Process('cd spec && cp stubs/.cigar.without-base.json .cigar.json && ../bin/cigar -u http://httpbin.org');
        $process->run();

        expect($process->getExitCode())->toBe(0);
    });

    it('can be passed a base URL as a full command argument', function () {
        $process = new Process('cd spec && cp stubs/.cigar.without-base.json .cigar.json && ../bin/cigar --url=http://httpbin.org');
        $process->run();

        expect($process->getExitCode())->toBe(0);
    });

    it('can be passed headers as full arguments', function () {
        $process = new Process('cd spec && cp stubs/.cigar.headers.json .cigar.json && ../bin/cigar --header="X-Cigar-Header: some-header-content-here" --header="X-Cigar-Header-2: more-header-content-here"');
        $process->run();

        expect($process->getExitCode())->toBe(0);
    });

    it('can be passed headers as short arguments', function () {
        $process = new Process('cd spec && cp stubs/.cigar.headers.json .cigar.json && ../bin/cigar -h "X-Cigar-Header: some-header-content-here" -h "X-Cigar-Header-2: more-header-content-here"');
        $process->run();

        expect($process->getExitCode())->toBe(0);
    });

    it('can be passed timeout arguments and it overwrites the configured value in .cigar.json', function () {
        $process = new Process('cd spec && cp stubs/.cigar.timeouts.json .cigar.json && ../bin/cigar -t 1');
        $process->run();

        expect($process->getExitCode())->toBe(1);
    });
});
