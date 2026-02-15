<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath power() function.
 *
 * Raises the input value to the specified power (x^exponent).
 * For single item: power(exponent), returns x^exponent
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class PowerFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('power');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        $exponentResult = $evaluator->evaluate($parameters[0], $context);
        if ($exponentResult->isEmpty()) {
            return Collection::empty();
        }

        $exponent = $exponentResult->first();
        if (!is_numeric($exponent)) {
            throw EvaluationException::invalidFunctionParameter('power', 'exponent', 'number');
        }

        $items = [];
        foreach ($input as $item) {
            if (!is_numeric($item)) {
                throw EvaluationException::invalidFunctionParameter('power', 'input', 'number');
            }

            $items[] = pow((float) $item, (float) $exponent);
        }

        return Collection::from($items);
    }
}
