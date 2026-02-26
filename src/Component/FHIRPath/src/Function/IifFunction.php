<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

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

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        // iif() cannot be applied to a multi-item collection
        if ($input->count() > 1) {
            throw new EvaluationException('iif() requires a single-item or empty input collection');
        }

        // Evaluate condition
        $conditionResult = $evaluator->evaluate($parameters[0], $context);

        // Condition must evaluate to a single boolean per FHIRPath spec
        if ($conditionResult->isEmpty()) {
            return Collection::empty();
        }

        if (!$conditionResult->isSingle()) {
            throw new EvaluationException('iif() condition must evaluate to a single boolean');
        }

        $condition = $conditionResult->first();

        if (!is_bool($condition)) {
            throw new EvaluationException('iif() condition must be a boolean value');
        }

        // If condition is true, evaluate and return ifTrue branch
        if ($condition === true) {
            return $evaluator->evaluate($parameters[1], $context);
        }

        // Otherwise, evaluate and return ifFalse branch
        return $evaluator->evaluate($parameters[2], $context);
    }
}
