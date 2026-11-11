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

        $evalContext = $this->focusContext($input, $context);

        // Evaluate condition
        $conditionResult = $evaluator->evaluateWithContext($parameters[0], $evalContext);

        // Per FHIRPath spec: if criterion is true, return true-result,
        // otherwise (false or empty) return otherwise-result (or empty if omitted).
        if ($conditionResult->isEmpty()) {
            if (count($parameters) >= 3) {
                return $evaluator->evaluateWithContext($parameters[2], $evalContext);
            }

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

    /**
     * Build the context the criterion and both branches are evaluated in, with the input as their focus.
     */
    private function focusContext(Collection $input, EvaluationContext $context): EvaluationContext
    {
        // A single item becomes the current node and $this, so condition/branches can reference
        // $this to mean the iif's focus (e.g. $this = 'context').
        if (!$input->isEmpty()) {
            return $context->withCurrentNode($input->first())->withBundleEntryScope($input->scopeAt(0))->withVariable('this', $input->first());
        }

        // A bare iif() on a null current node, such as an extension-only primitive in a raw array
        // inside select(), is already focused on that node, and $this names it. Clearing $this
        // would make `$this.exists()` false for an element that exists.
        if ($context->getCurrentNode() === null) {
            return $context;
        }

        // Otherwise the empty input came from navigation (`missing.iif(...)`, `{}.iif(...)`). Leave no
        // current node and no $this, so `empty()` or `exists()` see the empty input and a path such
        // as `active` does not navigate from the enclosing focus. ctm-1's
        // `member.resolve().iif(empty(), true, ...)` depends on this.
        return $context->withCurrentNode(null)->withBundleEntryScope(null)->withoutVariable('this');
    }
}
