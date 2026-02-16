<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * Returns true if any item in the collection is false.
 * Empty collection returns false.
 *
 * @author FHIR Tools Contributors
 */
class AnyFalseFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('anyFalse');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        foreach ($input as $item) {
            if ($item === false) {
                return Collection::single(true);
            }
        }

        return Collection::single(false);
    }
}
