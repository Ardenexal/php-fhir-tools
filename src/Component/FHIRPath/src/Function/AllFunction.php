<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * all(criteria) function - Returns true if all items match the criteria
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class AllFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('all');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        /** @var ExpressionNode $criteriaExpr */
        $criteriaExpr = $parameters[0];

        // Empty collection returns true (vacuous truth)
        if ($input->isEmpty()) {
            return Collection::single(true);
        }

        foreach ($input as $item) {
            $itemContext = $context->withCurrentNode($item);
            $result      = $criteriaExpr->accept($context->getEvaluator());

            // If any item doesn't match (false or empty), return false
            if ($result->isEmpty() || $result->first() !== true) {
                return Collection::single(false);
            }
        }

        return Collection::single(true);
    }
}
