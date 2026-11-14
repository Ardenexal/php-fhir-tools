<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;

/**
 * Returns items in this collection that are not in the other collection.
 *
 * @author FHIR Tools Contributors
 */
class ExcludeFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('exclude');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 1);

        /** @var ExpressionNode $otherExpr */
        $otherExpr   = $parameters[0];
        $otherResult = $this->evaluateParameter($otherExpr, $context);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        if ($otherResult->isEmpty()) {
            return $input;
        }

        // evaluateParameter() already unwrapped the exclusions; unwrap each input item the same way so
        // a FHIR primitive wrapper compares equal to its plain scalar.
        $normalizedOtherItems = $otherResult->toArray();

        $indexes = [];
        foreach ($input as $index => $item) {
            if (!in_array($context->normalizeValue($item), $normalizedOtherItems, true)) {
                $indexes[] = $index;
            }
        }

        return $input->only($indexes);
    }
}
