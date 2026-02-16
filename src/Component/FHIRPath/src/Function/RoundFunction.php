<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath round() function.
 *
 * Rounds a number to the nearest integer or to N decimal places if precision specified.
 * For single item: round([precision]), returns rounded number
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class RoundFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('round');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $precision = 0;
        if (!empty($parameters)) {
            $evaluator = $context->getEvaluator();
            if ($evaluator === null) {
                throw new EvaluationException('Evaluator not available in context');
            }

            $precisionResult = $evaluator->evaluate($parameters[0], $context);
            if (!$precisionResult->isEmpty()) {
                $precisionValue = $precisionResult->first();
                if (!is_numeric($precisionValue)) {
                    throw EvaluationException::invalidFunctionParameter('round', 'precision', 'number');
                }
                $precision = (int) $precisionValue;
            }
        }

        $items = [];
        foreach ($input as $item) {
            if (!is_numeric($item)) {
                throw EvaluationException::invalidFunctionParameter('round', 'input', 'number');
            }

            $items[] = round((float) $item, $precision);
        }

        return Collection::from($items);
    }
}
