<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * Returns true if all items in the collection are true.
 * Empty collection returns true.
 *
 * @author FHIR Tools Contributors
 */
class AllTrueFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('allTrue');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::single(true);
        }

        foreach ($input as $item) {
            if ($item !== true) {
                return Collection::single(false);
            }
        }

        return Collection::single(true);
    }
}
