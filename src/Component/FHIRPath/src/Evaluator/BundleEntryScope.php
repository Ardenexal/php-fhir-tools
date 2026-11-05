<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Evaluator;

/**
 * The Bundle entry a value was navigated out of.
 *
 * The meaning of a reference depends on the entry that holds it: `Patient/1` takes its base from that
 * entry's `fullUrl`, and `#id` looks in that entry's `contained`. A value cannot say where it came
 * from, because PHP arrays have no identity and two entries can hold identical references, so
 * {@see Collection} carries this alongside its items and resolve() reads it from there.
 *
 * $path names the entry by its Bundle's id and its position. It stays the same however often evaluation
 * navigates back to that entry, and costs nothing to compare.
 *
 * Equal values from different entries can merge into one item, for example in distinct(). A merged
 * scope remembers every origin in $origins, because the item means something in each of them.
 *
 * @author FHIR Tools Contributors
 */
final readonly class BundleEntryScope
{
    /**
     * @param list<self|null>|null $origins The origins of a merged item, null among them being one with
     *                                      no known entry. Empty when the origin cannot be known at all.
     */
    public function __construct(
        public BundleIndex $index,
        public mixed $entry,
        public string $path,
        public ?array $origins = null,
    ) {
    }

    /**
     * A scope for values whose origin cannot be known, so that nothing is resolved from them.
     */
    public static function unknowable(): self
    {
        return new self(new BundleIndex([]), null, 'unknowable', []);
    }

    /**
     * Combine the origins of an item that several items were merged into.
     *
     * @param list<self|null> $scopes
     */
    public static function merge(array $scopes): ?self
    {
        $origins = [];
        foreach ($scopes as $scope) {
            if ($scope?->origins === []) {
                return self::unknowable();
            }

            foreach ($scope === null ? [null] : ($scope->origins ?? [$scope]) as $origin) {
                $origins[$origin->path ?? ''] = $origin;
            }
        }

        if (count($origins) === 1) {
            return reset($origins);
        }

        return new self(new BundleIndex([]), null, 'merged:' . md5(implode('|', array_keys($origins))), array_values($origins));
    }
}
