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
     * @param array<int, mixed>            $items
     * @param bool                         $ordered      Whether items have a defined order (false for children(), descendants(), etc.)
     * @param string|null                  $declaredType FHIR type name declared by an `as` cast (e.g. 'Period'),
     *                                                   carried on empty collections so strict-mode property checks work.
     * @param array<int, BundleEntryScope> $scopes       The Bundle entry each item was navigated out of, keyed by
     *                                                   item index. map() and filter() drop these, because they can change an item's meaning.
     */
    private function __construct(
        private array $items,
        private bool $ordered = true,
        private ?string $declaredType = null,
        private array $scopes = [],
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
     * Create an unordered collection from an array of items.
     *
     * Used by functions such as children() and descendants() whose results are
     * not guaranteed to have a meaningful order per the FHIRPath specification.
     * Strict-mode guards on skip(), tail(), take(), and last() will reject these.
     *
     * @param array<int, mixed> $items
     */
    public static function unordered(array $items): self
    {
        return new self(array_values($items), false);
    }

    /**
     * Create a typed-empty collection carrying a FHIR declared type.
     *
     * Used by the strict-mode `as` operator when the cast returns empty — the type
     * annotation allows visitMemberAccess to validate property names against the
     * declared type even when the collection is empty.
     */
    public static function typedEmpty(string $type): self
    {
        return new self([], true, $type);
    }

    /**
     * Return whether this collection has a defined ordering.
     * Unordered collections are produced by children(), descendants(), etc.
     */
    public function isOrdered(): bool
    {
        return $this->ordered;
    }

    /**
     * Return the FHIR type declared by an `as` cast, or null if none.
     * Only set on empty collections produced by a failed strict-mode cast.
     */
    public function getDeclaredType(): ?string
    {
        return $this->declaredType;
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
     * Get the Bundle entry the item at the given index was navigated out of.
     */
    public function scopeAt(int $index): ?BundleEntryScope
    {
        return $this->scopes[$index] ?? null;
    }

    /**
     * Get the Bundle entries of the items that have one, keyed by item index.
     *
     * @return array<int, BundleEntryScope>
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * Return a copy with the given Bundle entries, keyed by item index.
     *
     * @param array<int, BundleEntryScope> $scopes
     */
    public function withScopes(array $scopes): self
    {
        // Runs on every member access, and outside a Bundle there is nothing to add.
        if ($scopes === [] && $this->scopes === []) {
            return $this;
        }

        return new self($this->items, $this->ordered, $this->declaredType, $scopes);
    }

    /**
     * Return a copy with every item in the given Bundle entry. Null leaves the collection as it is.
     */
    public function withScope(?BundleEntryScope $scope): self
    {
        if ($scope === null || $this->items === []) {
            return $this;
        }

        return $this->withScopes(array_fill(0, count($this->items), $scope));
    }

    /**
     * Get the one Bundle entry every item came from, if they all came from the same one.
     */
    public function soleScope(): ?BundleEntryScope
    {
        if ($this->items === [] || count($this->scopes) !== count($this->items)) {
            return null;
        }

        $sole = $this->scopes[0] ?? null;
        foreach ($this->scopes as $scope) {
            if ($scope !== $sole && $scope->path !== $sole?->path) {
                return null;
            }
        }

        return $sole;
    }

    /**
     * Return the items at the given indexes, each keeping its Bundle entry.
     *
     * The result is ordered, as it is from Collection::from(), which this stands in for in the
     * functions that select from their input.
     *
     * @param list<int> $indexes
     */
    public function only(array $indexes): self
    {
        $items = [];
        foreach ($indexes as $index) {
            $items[] = $this->items[$index];
        }

        if ($this->scopes === []) {
            return new self($items);
        }

        $scopes = [];
        foreach ($indexes as $position => $index) {
            if (isset($this->scopes[$index])) {
                $scopes[$position] = $this->scopes[$index];
            }
        }

        return new self($items, true, null, $scopes);
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
     * Perform a union with another collection (combines items, removes duplicates).
     *
     * Uses strict comparison (===) to determine uniqueness, ensuring that values
     * of different types (e.g., integer 1 and boolean true) are treated as distinct.
     * This aligns with FHIRPath's type-aware equality semantics.
     */
    public function union(self $other): self
    {
        // Per FHIRPath spec: union returns the combined collection with ALL duplicates removed
        // (not just cross-collection duplicates). Start with an empty result and add items
        // from both sides, skipping any that are already present.
        $result  = [];
        $origins = [];
        $tracked = $this->scopes !== [] || $other->scopes !== [];

        foreach ([$this, $other] as $side) {
            foreach ($side->items as $index => $item) {
                $position = array_search($item, $result, true);
                if ($position === false) {
                    $position = count($result);
                    $result[] = $item;
                }
                if ($tracked) {
                    $origins[$position][] = $side->scopes[$index] ?? null;
                }
            }
        }

        return new self($result, true, null, self::mergeOrigins($origins));
    }

    /**
     * Perform an intersection with another collection (keeps only common items)
     */
    public function intersect(self $other): self
    {
        $result  = [];
        $origins = [];
        $tracked = $this->scopes !== [] || $other->scopes !== [];

        foreach ($this->items as $index => $item) {
            $matches = array_keys($other->items, $item, true);
            if ($matches === []) {
                continue;
            }

            $position = array_search($item, $result, true);
            if ($position === false) {
                $position = count($result);
                $result[] = $item;
            }

            if ($tracked) {
                $origins[$position][] = $this->scopes[$index] ?? null;
                foreach ($matches as $match) {
                    $origins[$position][] = $other->scopes[$match] ?? null;
                }
            }
        }

        return new self($result, true, null, self::mergeOrigins($origins));
    }

    /**
     * Merge the origins gathered for each item of a deduplicated collection.
     *
     * @param array<int, list<BundleEntryScope|null>> $origins
     *
     * @return array<int, BundleEntryScope>
     */
    public static function mergeOrigins(array $origins): array
    {
        $scopes = [];
        foreach ($origins as $position => $itemOrigins) {
            $scope = BundleEntryScope::merge($itemOrigins);
            if ($scope !== null) {
                $scopes[$position] = $scope;
            }
        }

        return $scopes;
    }

    /**
     * Concatenate with another collection (combines items, allows duplicates)
     */
    public function concat(self $other): self
    {
        $scopes = $this->scopes;
        foreach ($other->scopes as $index => $scope) {
            $scopes[count($this->items) + $index] = $scope;
        }

        return new self([...$this->items, ...$other->items], true, null, $scopes);
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
