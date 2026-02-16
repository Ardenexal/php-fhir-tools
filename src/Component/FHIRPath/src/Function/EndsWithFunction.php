<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * Returns true if string ends with the given suffix.
 *
 * @author FHIR Tools Contributors
 */
class EndsWithFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('endsWith');
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

        /** @var ExpressionNode $suffixExpr */
        $suffixExpr = $parameters[0];
        $evaluator  = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }
        $suffixResult = $evaluator->evaluate($suffixExpr, $context);

        if ($suffixResult->isEmpty()) {
            return Collection::single(false);
        }

        $suffix = $suffixResult->first();
        if (!is_string($suffix)) {
            return Collection::single(false);
        }

        return Collection::single(str_ends_with($string, $suffix));
    }
}
