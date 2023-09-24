<?php

declare(strict_types=1);

namespace Brunty\Cigar\Tests\Unit;

use Brunty\Cigar\Result;
use Brunty\Cigar\Url;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public static function results(): array
    {
        return [
            'Status match, Content match, Content Type match' => [
                new Url('url', 200, 'content', 'text'),
                200,
                'content',
                'text',
                true,
            ],
            'Status mismatch, Content match, Content Type match' => [
                new Url('url', 201, 'content', 'text'),
                200,
                'content',
                'text',
                false,
            ],
            'Status match, Content match, Content Type mismatch' => [
                new Url('url', 200, 'content', 'text'),
                200,
                'content',
                'html',
                false,
            ],
            'Status match, Content mismatch, Content Type match' => [
                new Url('url', 200, 'content', 'text'),
                200,
                'some text',
                'text',
                false,
            ],
            'Status match, Content null, Content Type match' => [
                new Url('url', 200, null, 'TEXT'),
                200,
                'some text',
                'TExt',
                true,
            ],
            'Status match, Content null, Content Type null' => [
                new Url('url', 200, null, null),
                200,
                'some text',
                'text',
                true,
            ],
            'Status match, Content null mismatch, Content Type null' => [
                new Url('url', 200, 'content', null),
                200,
                null,
                'text',
                false,
            ],
        ];
    }

    #[Test]
    #[DataProvider('results')]
    public function it_returns_if_it_has_passed(
        Url $url,
        int $statusCode,
        ?string $contents,
        ?string $contentType,
        bool $hasPassed
    ): void {
        $result = new Result($url, $statusCode, $contents, $contentType);

        $this->assertSame($hasPassed, $result->hasPassed());
    }
}
