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
});
