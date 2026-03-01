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
        $this->validateParameterCount($parameters, 2, 3);

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        // iif() cannot be applied to a multi-item collection
        if ($input->count() > 1) {
            throw new EvaluationException('iif() requires a single-item or empty input collection');
        }

        // When input has a single item, expose it as $this and as the current node so that
        // condition/branches can reference $this to mean the iif's focus (e.g. $this = 'context').
        $evalContext = !$input->isEmpty()
            ? $context->withCurrentNode($input->first())->withVariable('this', $input->first())
            : $context;

        // Evaluate condition
        $conditionResult = $evaluator->evaluateWithContext($parameters[0], $evalContext);

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
            return $evaluator->evaluateWithContext($parameters[1], $evalContext);
        }

        // Otherwise, evaluate and return ifFalse branch (optional — returns empty if omitted)
        if (count($parameters) < 3) {
            return Collection::empty();
        }

        return $evaluator->evaluateWithContext($parameters[2], $evalContext);
    }
}
