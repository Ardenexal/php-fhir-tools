<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath combine() function.
 *
 * Combines two collections, keeping all duplicates (unlike union).
 * For input collection + other: combine(other), returns all items from both
 * For empty collection: returns other collection
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class CombineFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('combine');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1, 1);

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        $otherResult = $evaluator->evaluate($parameters[0], $context);

        // Combine keeps all items including duplicates
        $items = [];

        foreach ($input as $item) {
            $items[] = $item;
        }

        foreach ($otherResult as $item) {
            $items[] = $item;
        }

        return Collection::from($items);
    }
}
