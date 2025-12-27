<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

/**
 * Immutable collection abstraction for FHIRPath evaluation
 *
 * FHIRPath treats all values as collections (empty, single, or multiple items).
 * This class provides an immutable collection implementation with common operations.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 *
 * @implements \IteratorAggregate<int, mixed>
 */
final readonly class Collection implements \IteratorAggregate, \Countable
{
    /**
     * @param array<int, mixed> $items
     */
    private function __construct(
        private array $items
    ) {
    }

    /**
     * Create an empty collection
     */
    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * Create a collection with a single item
     */
    public static function single(mixed $item): self
    {
        return new self([$item]);
    }

    /**
     * Create a collection from an array of items
     *
     * @param array<int, mixed> $items
     */
    public static function from(array $items): self
    {
        return new self(array_values($items));
    }

    /**
     * Check if the collection is empty
     */
    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    /**
     * Check if the collection has exactly one item
     */
    public function isSingle(): bool
    {
        return count($this->items) === 1;
    }

    /**
     * Get the number of items in the collection
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Get the first item in the collection, or null if empty
     */
    public function first(): mixed
    {
        return $this->items[0] ?? null;
    }

    /**
     * Get the last item in the collection, or null if empty
     */
    public function last(): mixed
    {
        $count = count($this->items);

        return $count > 0 ? $this->items[$count - 1] : null;
    }

    /**
     * Get an item by index, or null if out of bounds
     */
    public function get(int $index): mixed
    {
        return $this->items[$index] ?? null;
    }

    /**
     * Get all items as an array
     *
     * @return array<int, mixed>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Map items to a new collection using a callback
     *
     * @param callable(mixed): mixed $callback
     */
    public function map(callable $callback): self
    {
        return new self(array_map($callback, $this->items));
    }

    /**
     * Filter items using a predicate callback
     *
     * @param callable(mixed): bool $predicate
     */
    public function filter(callable $predicate): self
    {
        return new self(array_values(array_filter($this->items, $predicate)));
    }

    /**
     * Perform a union with another collection (combines items, removes duplicates)
     */
    public function union(self $other): self
    {
        return new self(array_values(array_unique([...$this->items, ...$other->items], SORT_REGULAR)));
    }

    /**
     * Perform an intersection with another collection (keeps only common items)
     */
    public function intersect(self $other): self
    {
        return new self(array_values(array_intersect($this->items, $other->items)));
    }

    /**
     * Concatenate with another collection (combines items, allows duplicates)
     */
    public function concat(self $other): self
    {
        return new self([...$this->items, ...$other->items]);
    }

    /**
     * Check if all items satisfy a predicate
     *
     * @param callable(mixed): bool $predicate
     */
    public function all(callable $predicate): bool
    {
        foreach ($this->items as $item) {
            if (!$predicate($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if any item satisfies a predicate
     *
     * @param callable(mixed): bool $predicate
     */
    public function any(callable $predicate): bool
    {
        foreach ($this->items as $item) {
            if ($predicate($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Flatten a collection of collections into a single collection
     *
     * If any item is a Collection, its items are extracted. Non-Collection items
     * are included as-is.
     */
    public function flatten(): self
    {
        $result = [];
        foreach ($this->items as $item) {
            if ($item instanceof self) {
                $result = [...$result, ...$item->items];
            } else {
                $result[] = $item;
            }
        }

        return new self($result);
    }

    /**
     * Get an iterator for the collection
     *
     * @return \Traversable<int, mixed>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
