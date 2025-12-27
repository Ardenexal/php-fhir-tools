<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * Returns true if any item in the collection is true.
 * Empty collection returns false.
 *
 * @author FHIR Tools Contributors
 */
class AnyTrueFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('anyTrue');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        foreach ($input as $item) {
            if ($item === true) {
                return Collection::single(true);
            }
        }

        return Collection::single(false);
    }
}
