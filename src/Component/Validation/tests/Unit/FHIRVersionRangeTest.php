<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\FHIRVersionRange;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Ranges compare releases on major and minor version, include both bounds, and never exclude a
 * release they do not know.
 */
#[CoversClass(FHIRVersionRange::class)]
final class FHIRVersionRangeTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string|null, string|null, bool}>
     */
    public static function ranges(): iterable
    {
        yield 'no bounds' => ['R5', null, null, true];
        yield 'start bound is inclusive' => ['R4B', '4.3', null, true];
        yield 'before the start' => ['R4', '4.3', null, false];
        yield 'end bound is inclusive' => ['R4B', null, '4.3', true];
        yield 'after the end' => ['R5', null, '4.3', false];
        yield 'a patch version counts as its minor' => ['R4', '4.0.1', '4.0.1', true];
        yield 'a bare major counts as its .0 minor' => ['R5', '5', '5', true];
        yield 'an unknown release is never excluded' => ['R6', '4.0', '4.3', true];
    }

    #[DataProvider('ranges')]
    public function testIncludes(string $release, ?string $start, ?string $end, bool $included): void
    {
        self::assertSame($included, FHIRVersionRange::includes($release, $start, $end));
    }

    public function testNamesAndVersions(): void
    {
        self::assertSame('R4B', FHIRVersionRange::releaseNameOf('4.3'));
        self::assertSame('STU3', FHIRVersionRange::releaseNameOf('3.0.2'));
        self::assertSame('9.9', FHIRVersionRange::releaseNameOf('9.9'));
        self::assertSame('5.0.0', FHIRVersionRange::versionOf('R5'));
        self::assertNull(FHIRVersionRange::versionOf('R6'));
    }
}
