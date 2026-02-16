<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath avg() function.
 *
 * Returns the average (arithmetic mean) of the numeric values in the collection.
 * All items must be numeric.
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class AvgFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('avg');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $sum   = 0;
        $count = 0;

        foreach ($input as $item) {
            if (!is_numeric($item)) {
                throw EvaluationException::invalidFunctionParameter('avg', 'numeric values', gettype($item));
            }

            $sum += $item;
            ++$count;
        }

        return Collection::single($sum / $count);
    }
}
