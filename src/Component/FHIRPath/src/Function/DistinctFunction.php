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
            $key = $this->getItemKey($item);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $distinct[] = $item;
            }
        }

        return Collection::from($distinct);
    }

    private function getItemKey(mixed $item): string
    {
        if (is_scalar($item) || $item === null) {
            return serialize($item);
        }

        if (is_object($item)) {
            return spl_object_hash($item);
        }

        return serialize($item);
    }
}
