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

        // Handle Quantity: abs() the numeric value, preserve the unit
        if (is_array($value) && array_key_exists('value', $value) && is_numeric($value['value'])) {
            $result          = $value;
            $result['value'] = abs((float) $value['value']);

            return Collection::single($result);
        }

        $numeric = $this->extractNumeric($value);
        if ($numeric === null) {
            throw EvaluationException::invalidFunctionParameter($this->getName(), 'input', 'number');
        }

        return Collection::single(abs($numeric));
    }
}
