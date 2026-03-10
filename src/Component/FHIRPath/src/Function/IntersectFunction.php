<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * intersect(other) function - Returns the intersection of two collections
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class IntersectFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('intersect');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        /** @var ExpressionNode $otherExpr */
        $otherExpr       = $parameters[0];
        $otherCollection = $evaluator->evaluateWithContext($otherExpr, $context);

        return $input->intersect($otherCollection);
    }
}
