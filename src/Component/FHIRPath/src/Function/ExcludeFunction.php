<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Expression\ExpressionNode;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

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
        $otherExpr = $parameters[0];
        $evaluator = $context->getEvaluator();
        if ($evaluator === null) {
            throw new EvaluationException('Evaluator not available in context');
        }
        $otherResult = $evaluator->evaluate($otherExpr, $context);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        if ($otherResult->isEmpty()) {
            return $input;
        }

        // Normalize exclusion items so FHIR primitive wrappers compare equal to plain scalars
        $normalizedOtherItems = array_map(
            fn ($o) => $context->normalizeValue($o),
            $otherResult->toArray(),
        );

        $result = [];
        foreach ($input as $item) {
            if (!in_array($context->normalizeValue($item), $normalizedOtherItems, true)) {
                $result[] = $item;
            }
        }

        return Collection::from($result);
    }
}
