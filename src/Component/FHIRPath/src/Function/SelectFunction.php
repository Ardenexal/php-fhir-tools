<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * Transforms each item in collection using projection expression.
 *
 * @author FHIR Tools Contributors
 */
class SelectFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('select');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        /** @var ExpressionNode $projection */
        $projection = $parameters[0];
        $evaluator  = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        $results = [];

        foreach ($input as $index => $item) {
            $itemContext = $context
                ->withCurrentNode($item)
                ->withIterationVariables($item, $index, $input->count());

            $result = $evaluator->evaluate($projection, $itemContext);
            foreach ($result as $value) {
                $results[] = $value;
            }
        }

        return Collection::from($results);
    }
}
