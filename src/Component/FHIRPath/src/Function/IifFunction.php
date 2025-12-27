<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * FHIRPath iif() function.
 *
 * Conditional expression: if condition is true, returns ifTrue, else returns ifFalse.
 * For any input: iif(condition, ifTrue, ifFalse)
 * Condition must evaluate to a single boolean value.
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class IifFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('iif');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 3, 3);

        // Evaluate condition
        $conditionResult = $context->getEvaluator()->evaluate($parameters[0], $context);
        
        // If condition is empty or not boolean, return empty
        if ($conditionResult->isEmpty()) {
            return Collection::empty();
        }

        $condition = $conditionResult->first();
        
        // If condition is true, evaluate and return ifTrue branch
        if ($condition === true) {
            return $context->getEvaluator()->evaluate($parameters[1], $context);
        }
        
        // Otherwise, evaluate and return ifFalse branch
        return $context->getEvaluator()->evaluate($parameters[2], $context);
    }
}
