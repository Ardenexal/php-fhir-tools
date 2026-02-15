<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath floor() function.
 *
 * Returns the largest integer less than or equal to the input number.
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class FloorFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('floor');
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

        return Collection::single((int) floor((float) $value));
    }
}
