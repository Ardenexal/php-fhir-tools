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
            throw EvaluationException::invalidFunctionParameter(
                $this->getName(),
                'Input must be a number'
            );
        }

        return Collection::single(abs($value));
    }
}
