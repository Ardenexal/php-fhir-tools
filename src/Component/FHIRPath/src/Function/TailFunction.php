<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * tail() function - Returns all items except the first
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class TailFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('tail');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $items = [];
        $first = true;
        foreach ($input as $item) {
            if ($first) {
                $first = false;
                continue;
            }
            $items[] = $item;
        }

        return Collection::from($items);
    }
}
