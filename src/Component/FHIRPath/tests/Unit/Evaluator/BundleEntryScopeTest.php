<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Evaluator;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\BundleEntryScope;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\BundleIndex;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\BundleEntryScope
 */
final class BundleEntryScopeTest extends TestCase
{
    private function scope(string $path): BundleEntryScope
    {
        return new BundleEntryScope(new BundleIndex('bundle'), 'entry', $path);
    }

    public function testMergingOneOriginGivesThatOrigin(): void
    {
        $scope = $this->scope('0');

        self::assertSame($scope, BundleEntryScope::merge([$scope, $this->scope('0'), $scope]));
    }

    public function testMergingNoOriginsAtAllGivesNone(): void
    {
        self::assertNull(BundleEntryScope::merge([null, null]));
    }

    public function testMergingDifferentOriginsRemembersEach(): void
    {
        $first  = $this->scope('0');
        $second = $this->scope('1');

        self::assertSame([$first, $second], BundleEntryScope::merge([$first, $second, $first])?->origins);
    }

    public function testAnUnknownOriginCountsAsAnOrigin(): void
    {
        $scope = $this->scope('0');

        self::assertSame([$scope, null], BundleEntryScope::merge([$scope, null])?->origins);
    }

    public function testMergingFlattensEarlierMerges(): void
    {
        [$first, $second, $third] = [$this->scope('0'), $this->scope('1'), $this->scope('2')];

        $merged = BundleEntryScope::merge([BundleEntryScope::merge([$first, $second]), $third]);

        self::assertSame([$first, $second, $third], $merged?->origins);
    }

    public function testAnUnknowableScopeStaysThatWay(): void
    {
        self::assertSame([], BundleEntryScope::merge([$this->scope('0'), BundleEntryScope::unknowable()])?->origins);
    }

    public function testAMergedScopeHasAStablePath(): void
    {
        $first = BundleEntryScope::merge([$this->scope('0'), $this->scope('1')]);
        $again = BundleEntryScope::merge([$this->scope('0'), $this->scope('1')]);

        self::assertSame($first?->path, $again?->path);
        self::assertNotSame($first?->path, BundleEntryScope::merge([$this->scope('0'), $this->scope('2')])?->path);
    }
}
