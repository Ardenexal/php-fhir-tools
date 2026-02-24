<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * convertsToQuantity([unit : String]) : Boolean
 *
 * Returns true if the input value can be converted to a Quantity without error,
 * false if the conversion would fail. Returns empty {} if the input is empty.
 *
 * Accepts an optional unit parameter: if given, returns false when the converted
 * Quantity's unit does not match.
 *
 * Uses the same conversion rules as toQuantity().
 *
 * Spec reference: FHIRPath §5.5
 *
 * @author FHIR Tools Contributors
 */
final class ConvertsToQuantityFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('convertsToQuantity');
    }

    /**
     * Execute the convertsToQuantity check on the input.
     *
     * Tests whether the input value can be successfully converted to a Quantity,
     * optionally checking if the result would have a specific unit.
     *
     * @param Collection        $input      The input collection to test
     * @param array<int, mixed> $parameters [0] = optional unit filter expression
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item boolean (true if convertible, false otherwise), or empty if input is empty
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no unit filter) or 1 parameter (unit filter string)
        $this->validateParameterCount($parameters, 0, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $requiredUnit = $this->resolveUnitParameter($parameters, $context);

        // Try the conversion and check if it succeeded (returns non-null)
        return Collection::single(ToQuantityFunction::tryConvert($input->first(), $requiredUnit) !== null);
    }

    /**
     * Resolve the optional unit parameter from the parameter list.
     *
     * If a unit parameter was provided, this method evaluates it and returns
     * the unit string. Otherwise returns null.
     *
     * @param array<int, mixed> $parameters The function parameters
     * @param EvaluationContext $context    The evaluation context
     *
     * @return string|null The unit string if provided, null otherwise
     */
    private function resolveUnitParameter(array $parameters, EvaluationContext $context): ?string
    {
        // If no parameters provided, no unit filter to check
        if (count($parameters) !== 1) {
            return null;
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            return null;
        }

        $unitResult = $evaluator->evaluateWithContext($parameters[0], $context);
        if ($unitResult->isEmpty()) {
            return null;
        }

        $unitValue = $unitResult->first();

        return is_string($unitValue) ? $unitValue : null;
    }
}
