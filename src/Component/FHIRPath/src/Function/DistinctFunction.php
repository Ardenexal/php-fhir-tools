<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * Returns collection with duplicate values removed.
 *
 * @author FHIR Tools Contributors
 */
class DistinctFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('distinct');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $distinct = [];
        $seen     = [];

        foreach ($input as $item) {
            $key = $this->getItemKey($item, $context);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $distinct[] = $item;
            }
        }

        return Collection::from($distinct);
    }

    private function getItemKey(mixed $item, EvaluationContext $context): string
    {
        // Normalize FHIR primitive wrappers to their scalar values for key comparison
        // so that two StringPrimitive('Peter') objects are treated as equal.
        $normalized = $context->normalizeValue($item);

        if (is_scalar($normalized) || $normalized === null) {
            return serialize($normalized);
        }

        if (is_object($normalized)) {
            return spl_object_hash($normalized);
        }

        return serialize($normalized);
    }
}
