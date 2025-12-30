<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath sqrt() function.
 *
 * Returns the square root of the input value.
 * For single item: sqrt(), returns square root
 * For empty collection: returns empty
 * Throws exception if value < 0
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class SqrtFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('sqrt');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0, 0);

        if ($input->isEmpty()) {
            return Collection::empty();
        }

        $items = [];
        foreach ($input as $item) {
            if (!is_numeric($item)) {
                throw EvaluationException::invalidFunctionParameter('sqrt', 'numeric value', gettype($item));
            }

            $value = (float) $item;
            if ($value < 0) {
                throw EvaluationException::invalidFunctionParameter('sqrt', 'non-negative number', 'negative number');
            }

            $items[] = sqrt($value);
        }

        return Collection::from($items);
    }
}
