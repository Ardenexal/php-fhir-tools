<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * take(num) function - Returns the first num items from the collection
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class TakeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('take');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        $numCollection = $parameters[0];
        if (!($numCollection instanceof Collection) || $numCollection->isEmpty()) {
            throw EvaluationException::invalidFunctionParameter('take', 'num', 'non-empty integer collection');
        }

        $num = $numCollection->first();
        if (!is_int($num) || $num < 0) {
            throw EvaluationException::invalidFunctionParameter('take', 'num', 'non-negative integer');
        }

        if ($num === 0 || $input->isEmpty()) {
            return Collection::empty();
        }

        $items = [];
        $count = 0;
        foreach ($input as $item) {
            if ($count >= $num) {
                break;
            }
            $items[] = $item;
            ++$count;
        }

        return Collection::from($items);
    }
}
