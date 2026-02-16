<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;

/**
 * FHIRPath truncate() function.
 *
 * Returns the integer portion of a number (removes decimal part).
 * For single item: truncate(), returns integer part
 * For empty collection: returns empty
 *
 * @author Ardenexal <info@ardenexal.com>
 */
class TruncateFunction extends AbstractFunction
{
    public function __construct()
    {
        parent::__construct('truncate');
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
                throw EvaluationException::invalidFunctionParameter('truncate', 'numeric value', gettype($item));
            }

            $items[] = (int) $item;
        }

        return Collection::from($items);
    }
}
