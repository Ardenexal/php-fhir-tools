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

        $string = $input->first();
        if (!is_string($string)) {
            return Collection::single(false);
        }

        /** @var ExpressionNode $prefixExpr */
        $prefixExpr = $parameters[0];
        $evaluator  = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }
        $prefixResult = $evaluator->evaluate($prefixExpr, $context);

        if ($prefixResult->isEmpty()) {
            return Collection::single(false);
        }

        $prefix = $prefixResult->first();
        if (!is_string($prefix)) {
            return Collection::single(false);
        }

        return Collection::single(str_starts_with($string, $prefix));
    }
}
