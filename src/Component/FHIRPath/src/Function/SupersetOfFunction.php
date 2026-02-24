<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * supersetOf(other: collection) : Boolean
 *
 * Returns true if every item in `other` also exists in the input collection.
 * Returns true if `other` is empty (vacuous truth). Returns false if the input
 * is empty and `other` is not.
 *
 * Equality uses the same loose (==) semantics as the FHIRPath `=` operator.
 *
 * @author FHIR Tools Contributors
 */
final class SupersetOfFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('supersetOf');
    }

    /**
     * Execute the supersetOf check to test if input is a superset of another collection.
     *
     * Returns true if every item in the other collection exists in the input collection.
     *
     * @param Collection        $input      The input collection to check
     * @param array<int, mixed> $parameters [0] = expression evaluating to the other collection
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item collection with boolean result (true if superset, false otherwise)
     *
     * @throws EvaluationException If evaluator is not set in context
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects exactly 1 parameter (the other collection to compare against)
        $this->validateParameterCount($parameters, 1);

        /** @var ExpressionNode $otherExpr */
        $otherExpr = $parameters[0];
        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            // Error codes: 0 = line number (unknown), 0 = column number (unknown)
            throw new EvaluationException('Evaluator not set in context', 0, 0);
        }

        $other = $evaluator->evaluateWithContext($otherExpr, $context);

        // Any collection is a superset of an empty collection (vacuous truth)
        // "All zero items from other are in input" is always true
        if ($other->isEmpty()) {
            return Collection::single(true);
        }

        // Empty input cannot be a superset of a non-empty collection
        // If other has items but input is empty, those items can't be in input
        if ($input->isEmpty()) {
            return Collection::single(false);
        }

        // Check if every item in other exists in input
        foreach ($other as $otherItem) {
            if (!$this->collectionContains($input, $otherItem)) {
                // Found an item in other that's not in input - not a superset
                return Collection::single(false);
            }
        }

        // All items in other were found in input - it's a superset
        return Collection::single(true);
    }

    /**
     * Check if a collection contains a specific value.
     *
     * Uses loose equality (==) to match FHIRPath `=` operator semantics.
     * This means "1" == 1 (string equals number with type coercion).
     *
     * @param Collection $collection The collection to search in
     * @param mixed      $needle     The value to search for
     *
     * @return bool True if the value is found, false otherwise
     */
    private function collectionContains(Collection $collection, mixed $needle): bool
    {
        foreach ($collection as $item) {
            // Use loose equality (==) instead of strict (===) per FHIRPath spec
            // phpcs:ignore SlevomatCodingStandard.Operators.DisallowEqualOperators
            if ($item == $needle) {
                return true;
            }
        }

        return false;
    }
}
