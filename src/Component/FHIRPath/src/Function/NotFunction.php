<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * not() : Boolean
 *
 * Returns the boolean negation of the input collection. Follows FHIRPath spec §6.5.1:
 *   - empty input → empty
 *   - { true }   → { false }
 *   - { false }  → { true }
 *   - non-single or non-boolean → empty
 *
 * This is the function form of the `!` unary operator and is equivalent to it.
 *
 * @author FHIR Tools Contributors
 */
final class NotFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('not');
    }

    /**
     * Execute the not function to negate a boolean value.
     *
     * Returns the boolean negation of a single boolean input (true → false, false → true).
     * Returns empty for non-boolean, multi-item, or empty inputs.
     *
     * @param Collection        $input      The input collection (expects single boolean item)
     * @param array<int, mixed> $parameters No parameters expected (empty array)
     * @param EvaluationContext $context    The evaluation context
     *
     * @return Collection Single-item collection with negated boolean, or empty if input is invalid
     */
    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        // Expects 0 parameters (no parameters for not)
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            // No value to negate
            return Collection::empty();
        }

        if (!$input->isSingle()) {
            // Can only negate a single value, not multiple values
            return Collection::empty();
        }

        $value = $input->first();

        if (!is_bool($value)) {
            // Can only negate boolean values (true/false)
            return Collection::empty();
        }

        // Flip the boolean: true → false, false → true
        return Collection::single(!$value);
    }
}
