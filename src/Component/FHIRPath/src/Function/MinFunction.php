<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath min() function.
 *
 * Returns the minimum value in the collection.
 * All items must be numeric.
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class MinFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('min');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $min = null;
        foreach ($input as $item) {
            if (!is_numeric($item)) {
                throw EvaluationException::invalidFunctionParameter('min', 'numeric values', gettype($item));
            }

            if ($min === null || $item < $min) {
                $min = $item;
            }
        }

        return Collection::single($min);
    }
}
