<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * FHIRPath hasValue() function.
 *
 * Returns true if the input has a value (not just extensions).
 * For FHIR primitives, checks if the primitive value exists (not just extension).
 * For other types, returns true if not empty.
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class HasValueFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('hasValue');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $items = [];
        foreach ($input as $item) {
            // Check if object has a value property (FHIR primitive pattern)
            if (is_object($item)) {
                $hasValue = property_exists($item, 'value') && $item->value !== null;
                $items[]  = $hasValue;
            } else {
                // For non-objects, any non-null value is considered having value
                $items[] = $item !== null;
            }
        }

        return Collection::from($items);
    }
}
