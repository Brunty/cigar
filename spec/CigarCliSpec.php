<?php

use Symfony\Component\Process\Process;

describe('Cigar CLI Tool', function() {
    it('passes if given good URLs to check', function() {
        $process = new Process('cd spec && cp stubs/.cigar.pass.json .cigar.json && ../bin/cigar');
        $process->run();

        expect($process->getExitCode())->toBe(0);
    });

    it('is quiet if given the option', function() {
        $process = new Process('cd spec && cp stubs/.cigar.pass.json .cigar.json && ../bin/cigar --quiet');
        $process->run();

        expect($process->getOutput())->toBe('');
        expect($process->getExitCode())->toBe(0);
    });

    it('fails if given bad URLs to check', function() {
        $process = new Process('cd spec && cp .cigar.fail.json .cigar.json && ../bin/cigar');
        $process->run();

        expect($process->getExitCode())->toBe(1);
    });
});
