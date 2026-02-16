<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath ofType() function.
 *
 * Filters the collection to only items of the specified FHIR type.
 * For collection: ofType(typeName), returns items matching the type
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class OfTypeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('ofType');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1, 1);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }

        $typeResult = $evaluator->evaluate($parameters[0], $context);
        if ($typeResult->isEmpty()) {
            return Collection::empty();
        }

        $typeName = $typeResult->first();
        if (!is_string($typeName)) {
            throw EvaluationException::invalidFunctionParameter('ofType', 'typeName', 'string');
        }

        $items = [];
        foreach ($input as $item) {
            if (is_object($item)) {
                $className = get_class($item);
                $shortName = substr($className, strrpos($className, '\\') + 1);

                if ($shortName === $typeName || $className === $typeName) {
                    $items[] = $item;
                }
            }
        }

        return Collection::from($items);
    }
}
