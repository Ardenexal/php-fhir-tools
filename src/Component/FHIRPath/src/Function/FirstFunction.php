<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * first() function - Returns the first item in the collection
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class FirstFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('first');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        return Collection::single($input->first());
    }
}
