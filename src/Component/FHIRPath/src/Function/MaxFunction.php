<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath max() function.
 *
 * Returns the maximum value in the collection.
 * All items must be numeric.
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class MaxFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('max');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $max = null;
        foreach ($input as $item) {
            if (!is_numeric($item)) {
                throw EvaluationException::invalidFunctionParameter('max', 'numeric values', gettype($item));
            }

            if ($max === null || $item > $max) {
                $max = $item;
            }
        }

        return Collection::single($max);
    }
}
