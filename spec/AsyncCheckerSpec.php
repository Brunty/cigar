<?php

declare(strict_types=1);

use Brunty\Cigar\AsyncChecker;
use Brunty\Cigar\Result;
use Brunty\Cigar\Url;

describe('AsyncChecker', function (): void {
    it('checks a domain', function (): void {
        $domain = new Url('http://httpbin.org/status/200', 200);
        $domains = [$domain];

        $results = (new AsyncChecker())->check($domains);

        $expected = [
            new Result($domain, 200, '', 'text/html; charset=utf-8'),
        ];

        expect($results)->toEqual($expected);
    });

    it('checks more than one domain', function (): void {

        $domains = [
            new Url('http://httpbin.org/status/200', 200),
            new Url('http://httpbin.org/status/500', 500),
            new Url('http://httpbin.org/status/404', 404),
        ];

        $results = (new AsyncChecker())->check($domains);

        $expected = array_map(function (Url $domain) {
            return new Result($domain, $domain->status, '', 'text/html; charset=utf-8');
        }, $domains);

        expect($results)->toEqual($expected);
    });

    it('checks authorization header', function (): void {
        $domain = new Url('http://httpbin.org/get', 200);
        $expectedAuthHeader = 'Basic dXNyOnBzd2Q=';

        $results = (new AsyncChecker(false, $expectedAuthHeader))->check([$domain]);

        $decodedContent = json_decode($results[0]->contents, true);
        $actualAuthHeader = $decodedContent['headers']['Authorization'] ?? null;

        expect($actualAuthHeader)->toEqual($expectedAuthHeader);
    });

    context('when SSL verification is disabled', function (): void {
        it('checks a domain that has an invalid certificate', function (): void {
            $statusCode = 403;
            // Need to change for a better setup URL that doesn't default to a potentially unknown site
            $domain = new Url('https://cigar-do-not-work.apps.brunty.me', $statusCode);
            $domains = [$domain];

            $results = (new AsyncChecker(false))->check($domains);

            $expected = [new Result($domain, $statusCode)];

            expect($results[0]->statusCode)->toEqual($expected[0]->statusCode);
        });
    });

    context('when timeouts are set', function (): void {
        it('checks a URL that will timeout', function (): void {
            // Need to change for a better setup URL that doesn't default to a potentially unknown site
            $domain = new Url('https://httpbin.org/delay/3', 200, null, null, 1, 1);
            $domains = [$domain];

            $results = (new AsyncChecker())->check($domains);

            $expected = [
                new Result($domain, 0),
            ];

            expect($results[0]->statusCode)->toEqual($expected[0]->statusCode);
        });
    });
});
