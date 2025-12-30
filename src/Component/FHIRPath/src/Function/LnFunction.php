<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath ln() function.
 *
 * Returns the natural logarithm (base e) of the input value.
 * For single item: ln(), returns natural logarithm
 * For empty collection: returns empty
 * Throws exception if value <= 0
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class LnFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('ln');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $items = [];
        foreach ($input as $item) {
            if (!is_numeric($item)) {
                throw EvaluationException::invalidFunctionParameter('ln', 'numeric value', gettype($item));
            }

            $value = (float) $item;
            if ($value <= 0) {
                throw EvaluationException::invalidFunctionParameter('ln', 'positive number', 'number <= 0');
            }

            $items[] = log($value);
        }

        return Collection::from($items);
    }
}
