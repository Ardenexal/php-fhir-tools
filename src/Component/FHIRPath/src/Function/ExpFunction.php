<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath exp() function.
 *
 * Returns e raised to the power of the input value (e^x).
 * For single item: exp(), returns e^x
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class ExpFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('exp');
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
                throw EvaluationException::invalidFunctionParameter('exp', 'numeric value', gettype($item));
            }

            $items[] = exp((float) $item);
        }

        return Collection::from($items);
    }
}
