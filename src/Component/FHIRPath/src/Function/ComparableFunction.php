<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath comparable() function.
 *
 * Returns true if the input value can be compared (ordered) against the given
 * parameter value — i.e. both values exist and are of the same comparable type
 * (numeric, string, or boolean).
 *
 * Returns false when the types are incompatible or either operand is absent.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class ComparableFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('comparable');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        if ($input->isEmpty()) {
            return Collection::single(false);
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        $paramResult = $evaluator->evaluate($parameters[0], $context);
        if ($paramResult->isEmpty()) {
            return Collection::single(false);
        }

        $inputValue = $input->first();
        $paramValue = $paramResult->first();

        // Both must be the same comparable type.
        // Integers and floats are mutually comparable (numeric).
        $inputType = $this->comparableType($inputValue);
        $paramType = $this->comparableType($paramValue);

        if ($inputType === null || $paramType === null) {
            return Collection::single(false);
        }

        return Collection::single($inputType === $paramType);
    }

    /**
     * Returns a normalised comparable type tag for a value, or null when the value
     * is not an orderable type (e.g. objects, arrays).
     */
    private function comparableType(mixed $value): ?string
    {
        if (is_int($value) || is_float($value)) {
            return 'numeric';
        }

        if (is_string($value)) {
            return 'string';
        }

        if (is_bool($value)) {
            return 'boolean';
        }

        return null;
    }
}
