<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * Returns true if string contains the given substring.
 *
 * @author FHIR Tools Contributors
 */
class ContainsStringFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('contains');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $string = $input->first();
        if (!is_string($string)) {
            return Collection::single(false);
        }

        /** @var ExpressionNode $substringExpr */
        $substringExpr = $parameters[0];
        $evaluator = $context->getEvaluator();
        $substringResult = $evaluator->evaluate($substringExpr, $context);

        if ($substringResult->isEmpty()) {
            return Collection::single(false);
        }

        $substring = $substringResult->first();
        if (!is_string($substring)) {
            return Collection::single(false);
        }

        return Collection::single(str_contains($string, $substring));
    }
}
