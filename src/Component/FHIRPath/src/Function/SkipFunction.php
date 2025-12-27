<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * skip(num) function - Returns all items except the first num
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class SkipFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('skip');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        $numCollection = $parameters[0];
        if (!($numCollection instanceof Collection) || $numCollection->isEmpty()) {
            throw EvaluationException::invalidFunctionParameter('skip', 'num', 'non-empty integer collection');
        }

        $num = $numCollection->first();
        if (!is_int($num) || $num < 0) {
            throw EvaluationException::invalidFunctionParameter('skip', 'num', 'non-negative integer');
        }

        if ($num === 0) {
            return $input;
        }

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $items = [];
        $count = 0;
        foreach ($input as $item) {
            if ($count < $num) {
                ++$count;
                continue;
            }
            $items[] = $item;
        }

        return Collection::from($items);
    }
}
