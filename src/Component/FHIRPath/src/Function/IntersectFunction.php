<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * intersect(other) function - Returns the intersection of two collections
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class IntersectFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('intersect');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        $otherCollection = $parameters[0];
        if (!$otherCollection instanceof Collection) {
            return Collection::empty();
        }

        return $input->intersect($otherCollection);
    }
}
