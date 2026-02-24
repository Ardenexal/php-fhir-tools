<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * subsetOf(other: collection) : Boolean
 *
 * Returns true if every item in the input collection also exists in `other`.
 * Returns true if the input is empty (vacuous truth). Returns false if `other`
 * is empty and the input is not.
 *
 * Equality uses the same loose (==) semantics as the FHIRPath `=` operator.
 *
 * @author FHIR Tools Contributors
 */
final class SubsetOfFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('subsetOf');
    }

    /**
     * Execute the subsetOf check to test if input is a subset of another collection.
     *
     * Returns true if every item in the input collection exists in the other collection.
     *
     * @param Collection        $input      The input collection to check
     * @param array<int, mixed> $parameters [0] = expression evaluating to the other collection
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item collection with boolean result (true if subset, false otherwise)
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

        // Empty input is a subset of any collection (vacuous truth)
        // "All zero items are contained in other" is always true
        if ($input->isEmpty()) {
            return Collection::single(true);
        }

        // Non-empty input cannot be a subset of an empty collection
        // If input has items but other is empty, those items can't be in other
        if ($other->isEmpty()) {
            return Collection::single(false);
        }

        // Check if every item in input exists in other
        foreach ($input as $item) {
            if (!$this->collectionContains($other, $item)) {
                // Found an item in input that's not in other - not a subset
                return Collection::single(false);
            }
        }

        // All items in input were found in other - it's a subset
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
