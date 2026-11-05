<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Evaluator;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\BundleEntryScope;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\BundleIndex;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection
 */
final class CollectionTest extends TestCase
{
    public function testEmptyCollection(): void
    {
        $collection = Collection::empty();

        self::assertTrue($collection->isEmpty());
        self::assertFalse($collection->isSingle());
        self::assertSame(0, $collection->count());
        self::assertNull($collection->first());
        self::assertNull($collection->last());
        self::assertSame([], $collection->toArray());
    }

    public function testSingleItemCollection(): void
    {
        $collection = Collection::single('value');

        self::assertFalse($collection->isEmpty());
        self::assertTrue($collection->isSingle());
        self::assertSame(1, $collection->count());
        self::assertSame('value', $collection->first());
        self::assertSame('value', $collection->last());
        self::assertSame(['value'], $collection->toArray());
    }

    public function testMultipleItemCollection(): void
    {
        $collection = Collection::from(['a', 'b', 'c']);

        self::assertFalse($collection->isEmpty());
        self::assertFalse($collection->isSingle());
        self::assertSame(3, $collection->count());
        self::assertSame('a', $collection->first());
        self::assertSame('c', $collection->last());
        self::assertSame(['a', 'b', 'c'], $collection->toArray());
    }

    public function testGetByIndex(): void
    {
        $collection = Collection::from(['a', 'b', 'c']);

        self::assertSame('a', $collection->get(0));
        self::assertSame('b', $collection->get(1));
        self::assertSame('c', $collection->get(2));
        self::assertNull($collection->get(3));
        self::assertNull($collection->get(-1));
    }

    public function testMap(): void
    {
        $collection = Collection::from([1, 2, 3]);
        $result     = $collection->map(fn ($x) => $x * 2);

        self::assertSame([2, 4, 6], $result->toArray());
    }

    public function testFilter(): void
    {
        $collection = Collection::from([1, 2, 3, 4, 5]);
        $result     = $collection->filter(fn ($x) => $x % 2 === 0);

        self::assertSame([2, 4], $result->toArray());
    }

    public function testUnion(): void
    {
        $collection1 = Collection::from([1, 2, 3]);
        $collection2 = Collection::from([3, 4, 5]);
        $result      = $collection1->union($collection2);

        self::assertSame([1, 2, 3, 4, 5], $result->toArray());
    }

    public function testUnionWithMixedTypes(): void
    {
        // FHIRPath union should preserve both integer 1 and boolean true as distinct values
        $collection1 = Collection::from([1]);
        $collection2 = Collection::from([true]);
        $result      = $collection1->union($collection2);

        self::assertSame([1, true], $result->toArray());
        self::assertSame(2, $result->count());
    }

    public function testUnionWithIntegerAndBooleanPreservesDistinctTypes(): void
    {
        // Regression test for precedence bug: (1 | 1 is Integer).count() should return 2
        // The expression evaluates to (1 | true), which should be [1, true] not [1]
        $collection1 = Collection::from([1, 2]);
        $collection2 = Collection::from([true, false]);
        $result      = $collection1->union($collection2);

        self::assertSame([1, 2, true, false], $result->toArray());
        self::assertSame(4, $result->count());
    }

    public function testUnionRemovesDuplicatesWithStrictComparison(): void
    {
        // Ensure identical values (same type and value) are deduplicated
        $collection1 = Collection::from([1, 2, 3]);
        $collection2 = Collection::from([2, 3, 4]);
        $result      = $collection1->union($collection2);

        self::assertSame([1, 2, 3, 4], $result->toArray());
    }

    public function testIntersect(): void
    {
        $collection1 = Collection::from([1, 2, 3, 4]);
        $collection2 = Collection::from([3, 4, 5, 6]);
        $result      = $collection1->intersect($collection2);

        self::assertSame([3, 4], $result->toArray());
    }

    public function testConcat(): void
    {
        $collection1 = Collection::from([1, 2]);
        $collection2 = Collection::from([3, 4]);
        $result      = $collection1->concat($collection2);

        self::assertSame([1, 2, 3, 4], $result->toArray());
    }

    public function testConcatAllowsDuplicates(): void
    {
        $collection1 = Collection::from([1, 2, 3]);
        $collection2 = Collection::from([3, 4, 5]);
        $result      = $collection1->concat($collection2);

        self::assertSame([1, 2, 3, 3, 4, 5], $result->toArray());
    }

    public function testAll(): void
    {
        $collection = Collection::from([2, 4, 6]);

        self::assertTrue($collection->all(fn ($x) => $x % 2 === 0));
        self::assertFalse($collection->all(fn ($x) => $x > 5));
    }

    public function testAny(): void
    {
        $collection = Collection::from([1, 2, 3]);

        self::assertTrue($collection->any(fn ($x) => $x === 2));
        self::assertFalse($collection->any(fn ($x) => $x > 5));
    }

    public function testFlatten(): void
    {
        $inner1     = Collection::from([1, 2]);
        $inner2     = Collection::from([3, 4]);
        $collection = Collection::from([$inner1, 5, $inner2]);

        $result = $collection->flatten();

        self::assertSame([1, 2, 5, 3, 4], $result->toArray());
    }

    public function testIterator(): void
    {
        $collection = Collection::from(['a', 'b', 'c']);
        $items      = [];

        foreach ($collection as $item) {
            $items[] = $item;
        }

        self::assertSame(['a', 'b', 'c'], $items);
    }

    public function testItemsHaveNoScopeByDefault(): void
    {
        $collection = Collection::from(['a', 'b']);

        self::assertNull($collection->scopeAt(0));
        self::assertSame([], $collection->getScopes());
    }

    public function testWithScopeAppliesToEveryItem(): void
    {
        $scope      = new BundleEntryScope(new BundleIndex('bundle'), 'entry', '0');
        $collection = Collection::from(['a', 'b'])->withScope($scope);

        self::assertSame($scope, $collection->scopeAt(0));
        self::assertSame($scope, $collection->scopeAt(1));
        self::assertSame(['a', 'b'], $collection->toArray());
    }

    public function testWithNullScopeLeavesTheCollectionAsItIs(): void
    {
        $collection = Collection::from(['a']);

        self::assertSame($collection, $collection->withScope(null));
    }

    public function testWithScopeOnAnEmptyCollectionAddsNothing(): void
    {
        self::assertSame([], Collection::empty()->withScope(new BundleEntryScope(new BundleIndex('bundle'), 'entry', '0'))->getScopes());
    }

    public function testOnlySelectsItemsAndRekeysTheirScopes(): void
    {
        $first      = new BundleEntryScope(new BundleIndex('bundle'), 'first', '0');
        $last       = new BundleEntryScope(new BundleIndex('bundle'), 'last', '0');
        $collection = Collection::from(['a', 'b', 'c'])->withScopes([0 => $first, 2 => $last]);

        $selected = $collection->only([2, 1, 0]);

        self::assertSame(['c', 'b', 'a'], $selected->toArray());
        self::assertSame($last, $selected->scopeAt(0));
        self::assertNull($selected->scopeAt(1));
        self::assertSame($first, $selected->scopeAt(2));
    }

    public function testOnlyReturnsAnOrderedCollection(): void
    {
        // where() and friends have always handed back an ordered collection from Collection::from(),
        // which is what the strict-mode ordering guards on skip(), tail() and take() see.
        self::assertTrue(Collection::unordered(['a', 'b'])->only([0])->isOrdered());
    }

    public function testMappingAndFilteringDropScopes(): void
    {
        $collection = Collection::from(['a', 'b'])->withScope(new BundleEntryScope(new BundleIndex('bundle'), 'entry', '0'));

        self::assertSame([], $collection->map(static fn (mixed $item): mixed => $item)->getScopes());
        self::assertSame([], $collection->filter(static fn (mixed $item): bool => true)->getScopes());
    }

    public function testConcatKeepsTheScopesOfBothSides(): void
    {
        $left  = new BundleEntryScope(new BundleIndex('bundle'), 'left', '0');
        $right = new BundleEntryScope(new BundleIndex('bundle'), 'right', '1');

        $combined = Collection::single('a')->withScope($left)->concat(Collection::single('b')->withScope($right));

        self::assertSame($left, $combined->scopeAt(0));
        self::assertSame($right, $combined->scopeAt(1));
    }

    public function testUnionKeepsAScopeOnItemsFromOneOrigin(): void
    {
        $scope = new BundleEntryScope(new BundleIndex('bundle'), 'entry', '0');

        $union = Collection::from(['a', 'a'])->withScope($scope)->union(Collection::single('b'));

        self::assertSame(['a', 'b'], $union->toArray());
        self::assertSame($scope, $union->scopeAt(0));
        self::assertNull($union->scopeAt(1));
    }

    public function testUnionMergesTheOriginsOfEqualItems(): void
    {
        $left  = new BundleEntryScope(new BundleIndex('bundle'), 'left', '0');
        $right = new BundleEntryScope(new BundleIndex('bundle'), 'right', '1');

        $union = Collection::single('a')->withScope($left)->union(Collection::single('a')->withScope($right));

        self::assertCount(1, $union);
        self::assertSame([$left, $right], $union->scopeAt(0)?->origins);
    }

    public function testIntersectMergesTheOriginsOfEqualItems(): void
    {
        $left  = new BundleEntryScope(new BundleIndex('bundle'), 'left', '0');
        $right = new BundleEntryScope(new BundleIndex('bundle'), 'right', '1');

        $both = Collection::from(['a', 'c'])->withScope($left)->intersect(Collection::from(['a', 'b'])->withScope($right));

        self::assertSame(['a'], $both->toArray());
        self::assertSame([$left, $right], $both->scopeAt(0)?->origins);
    }

    public function testSoleScopeIsOnlyAnsweredWhenEveryItemSharesOne(): void
    {
        $first  = new BundleEntryScope(new BundleIndex('bundle'), 'first', '0');
        $second = new BundleEntryScope(new BundleIndex('bundle'), 'second', '1');

        self::assertSame($first, Collection::from(['a', 'b'])->withScope($first)->soleScope());
        self::assertNull(Collection::from(['a', 'b'])->withScopes([0 => $first, 1 => $second])->soleScope());
        self::assertNull(Collection::from(['a', 'b'])->withScopes([0 => $first])->soleScope());
        self::assertNull(Collection::from(['a'])->soleScope());
    }
}
