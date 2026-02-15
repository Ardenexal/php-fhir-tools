<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * union(other) function - Returns the union of two collections (no duplicates)
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class UnionFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('union');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        $otherCollection = $parameters[0];
        if (!$otherCollection instanceof Collection) {
            return $input;
        }

        return $input->union($otherCollection);
    }
}
