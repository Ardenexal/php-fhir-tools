<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * Returns true if string starts with the given prefix.
 *
 * @author FHIR Tools Contributors
 */
class StartsWithFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('startsWith');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $string = $context->normalizeValue($input->first());
        if (!is_string($string)) {
            throw new EvaluationException('startsWith() requires a string input');
        }

        /** @var ExpressionNode $prefixExpr */
        $prefixExpr = $parameters[0];
        $evaluator  = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }
        // Evaluate the argument against the CURRENT NODE, passing the context separately.
        // `evaluate()` takes (expression, resource, context) — the widespread
        // `evaluate($expr, $context)` idiom accidentally supplies the context object as the resource.
        // That is harmless for literal arguments but breaks any argument that navigates: in sdf-8a,
        // `path.startsWith(%resource.type)` resolved `%resource` against an EvaluationContext, got
        // nothing, and answered false — flagging valid StructureDefinitions.
        $prefixResult = $evaluator->evaluate($prefixExpr, $context->getCurrentNode(), $context);

        if ($prefixResult->isEmpty()) {
            return Collection::single(false);
        }

        // Normalize the prefix exactly as the input is normalized above. Navigating to a FHIR
        // primitive yields a typed wrapper rather than a bare string, so an un-normalized prefix
        // fails is_string() and the function silently answers false. That is how
        // `element.first().path.startsWith(%resource.type)` in sdf-8a reported
        // 'Device.identifier'.startsWith('Device') as false and flagged valid StructureDefinitions.
        $prefix = $context->normalizeValue($prefixResult->first());
        if (!is_string($prefix)) {
            return Collection::single(false);
        }

        return Collection::single(str_starts_with($string, $prefix));
    }
}
