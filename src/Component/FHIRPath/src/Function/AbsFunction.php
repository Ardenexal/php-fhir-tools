<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath abs() function.
 *
 * Returns the absolute value of the input number.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class AbsFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('abs');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $value = $input->first();
        if (!is_numeric($value)) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'input', 'number');
        }

        // Ensure we have a numeric value for abs()
        $numericValue = is_string($value) ? (float) $value : $value;

        return Collection::single(abs($numericValue));
    }
}
