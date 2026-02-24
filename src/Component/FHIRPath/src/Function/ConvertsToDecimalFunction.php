<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * convertsToDecimal(): Boolean
 *
 * Returns true if the input value can be converted to a Decimal without error,
 * false if the conversion would fail. Returns empty {} if the input is empty.
 *
 * Uses the same conversion rules as toDecimal().
 *
 * Spec reference: FHIRPath §5.5
 *
 * @author FHIR Tools Contributors
 */
final class ConvertsToDecimalFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('convertsToDecimal');
    }

    /**
     * Execute the convertsToDecimal check on the input.
     *
     * Tests whether the input value can be successfully converted to a decimal (float).
     *
     * @param Collection        $input      The input collection to test
     * @param array<int, mixed> $parameters No parameters expected (empty array)
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item boolean (true if convertible, false otherwise), or empty if input is empty
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no parameters for convertsToDecimal)
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        // Try the conversion and check if it succeeded (returns non-null)
        return Collection::single(ToDecimalFunction::tryConvert($input->first()) !== null);
    }
}
