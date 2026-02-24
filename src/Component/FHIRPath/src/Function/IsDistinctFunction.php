<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * isDistinct() : Boolean
 *
 * Returns true if all items in the collection are distinct (no duplicates).
 * Returns true for empty collections and single-item collections.
 *
 * Uses the same key strategy as distinct(): serialize() for scalars/null and
 * spl_object_hash() for objects, which matches object-identity semantics.
 *
 * @author FHIR Tools Contributors
 */
final class IsDistinctFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('isDistinct');
    }

    /**
     * Execute the isDistinct check on the input collection.
     *
     * Checks if all items in the collection are unique (no duplicates). Returns true
     * for empty or single-item collections.
     *
     * @param Collection        $input      The input collection to check
     * @param array<int, mixed> $parameters No parameters expected (empty array)
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item collection with boolean result (true if distinct, false otherwise)
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no parameters for isDistinct)
        $this->validateParameterCount($parameters, 0);

        // Empty or single-item collections are trivially distinct
        // (You can't have duplicates if there's 0 or 1 items)
        if ($input->isEmpty() || $input->isSingle()) {
            return Collection::single(true);
        }

        $seen = []; // Track items we've already encountered

        foreach ($input as $item) {
            $key = $this->itemKey($item);

            if (isset($seen[$key])) {
                // Found a duplicate - collection is NOT distinct
                return Collection::single(false);
            }

            $seen[$key] = true; // Mark this item as seen
        }

        // Made it through all items without finding duplicates
        return Collection::single(true);
    }

    /**
     * Generate a unique key for an item to detect duplicates.
     *
     * This method creates consistent keys for comparison:
     *   - Scalars (strings, numbers, booleans) and null: serialized value
     *   - Objects: unique object instance identifier
     *   - Arrays: serialized value
     *
     * @param mixed $item The item to generate a key for
     *
     * @return string A unique string identifier for this item
     */
    private function itemKey(mixed $item): string
    {
        // For simple values (strings, numbers, booleans, null), serialize them
        if (is_scalar($item) || $item === null) {
            return serialize($item);
        }

        // For objects, use PHP's built-in object hash (unique per object instance)
        // Two different object instances are considered different even if they have same properties
        if (is_object($item)) {
            return spl_object_hash($item);
        }

        // For arrays and other types, serialize them
        return serialize($item);
    }
}
