<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * exists([criteria]) function - Returns true if collection has any items (optionally matching criteria)
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class ExistsFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('exists');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 1);

        // No criteria - check if any items exist
        if (count($parameters) === 0) {
            return Collection::single(!$input->isEmpty());
        }

        // With criteria - check if any item matches
        /** @var ExpressionNode $criteriaExpr */
        $criteriaExpr = $parameters[0];

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not set in context', 0, 0);
        }

        foreach ($input as $item) {
            $itemContext = $context->withCurrentNode($item);
            $result      = $criteriaExpr->accept($evaluator);

            if (!$result->isEmpty() && $result->first() === true) {
                return Collection::single(true);
            }
        }

        return Collection::single(false);
    }
}
