<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * sum() function - Returns the sum of all numeric values in the collection
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class SumFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('sum');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $sum = 0;
        foreach ($input as $item) {
            if (is_int($item) || is_float($item)) {
                $sum += $item;
            }
        }

        return Collection::single($sum);
    }
}
