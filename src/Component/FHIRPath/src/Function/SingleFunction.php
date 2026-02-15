<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * Returns the single item in collection. Throws if not exactly one item.
 *
 * @author FHIR Tools Contributors
 */
class SingleFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('single');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        if ($input->isEmpty()) {
            throw EvaluationException::invalidFunctionParameter('single', 'collection', 'non-empty collection');
        }

        if ($input->count() > 1) {
            throw EvaluationException::invalidFunctionParameter('single', 'collection', 'single item collection');
        }

        return $input;
    }
}
