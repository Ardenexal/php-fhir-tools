<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * last() function - Returns the last item in the collection
 *
 * @author Ardenexal <https://github.com/Ardenexal>
 */
final class LastFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('last');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        return Collection::single($input->last());
    }
}
