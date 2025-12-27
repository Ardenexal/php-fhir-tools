<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Evaluator;

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
}
