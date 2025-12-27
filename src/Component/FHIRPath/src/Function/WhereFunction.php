<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * where(criteria) function - Filters the collection by criteria expression
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class WhereFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('where');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        /** @var ExpressionNode $criteriaExpr */
        $criteriaExpr = $parameters[0];

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not set in context', 0, 0);
        }

        return $input->filter(function($item) use ($criteriaExpr, $context, $evaluator) {
            $itemContext = $context->withCurrentNode($item);
            $result      = $criteriaExpr->accept($evaluator);

            // Item matches if result is true
            return !$result->isEmpty() && $result->first() === true;
        });
    }
}
